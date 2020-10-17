<?php 
  require_once 'connect_db.php';

  require_once 'p.php';

  
  

  // Проверяем заполнение всех необходимых значений
  if 
  (
    (!empty($_POST['series']) || !empty($_POST['model'])) && 
    !empty($_POST['price_1_pcs']) && 
    !empty($_POST['price_2_pcs']) && 
    !empty($_POST['price_5_pcs']) &&
    !empty($_POST['price_in_office']) &&
    (!empty($_POST['printer_series']) || !empty($_POST['printer_model']))
  ) {
    // Разбиваем по разделителю строку на разные серии принтеров
    $printer_series = explode('|', $_POST['printer_series']);
    array_walk($printer_series, 'trim_value'); 
    if (count($printer_series)>=1 && !empty($printer_series[0])) {
      delete_empty_values($printer_series);      
    } else $printer_series[0] = '';
    

    // Разбиваем по разделителю модели принтеров для каждой серии
    $printer_model = explode('|', $_POST['printer_model']);
    array_walk($printer_model, 'trim_value'); 
    delete_empty_values($printer_model);

    // Объединяем серии и модели принтеров в ассоциативный массив
    $printers = array_combine($printer_series, $printer_model);
    
    // Если данные введены верно, то получаем отдельно каждую модель 
    if ($printers) {
      foreach ($printers as &$v) {
        $v = explode('/', $v);
        array_walk($v, 'trim_value');
        delete_empty_values($v);
      }

    } else {
      header("Location: /add_data_to_db.php?err=1");
      exit();
    }
  } else {
    header("Location: /add_data_to_db.php?err=2");
    exit();
  }

  // Готовим к вставке цвет принтера
  $colored = ($_POST['colored'] == '1') ? 1 : 0;

  //Проверяем наличие серии принтера и подготавливаем к вставке
  if (empty($_POST['printer_series'])) $_POST['printer_series'] = NULL; 

  //Создаем переменные для текста запросов
  $query_cartriges = '';
  $query_printers  = '';
  $query_match     = '';

  // Формируем SQL запрос
  // Вставка в таблицу картриджей
  $query_cartriges .= "INSERT cartriges 
  (
    color_id, 
    series, 
    model, 
    price_1_pcs, 
    price_2_pcs, 
    price_5_pcs, 
    price_in_office
  ) 
  SELECT
    (SELECT color_id FROM colors WHERE color_name = :color_name),
    :cartrige_series,
    :cartrige_model,
    :price_1_pcs,
    :price_2_pcs,
    :price_5_pcs,
    :price_in_office
  WHERE NOT EXISTS
  (SELECT series, model FROM cartriges
  WHERE series = :cartrige_series AND model = :cartrige_model) LIMIT 1;";
  
  //Вставка в таблицу принтеров каждого значения из массива, если этого
  //принтера еще нет в таблице.
  // В этом цикле вставляем значения в таблицу соответствия принтеров и картриджей
  foreach ($printers as $series => $models) {
    foreach ($models as $model) {
      $query_printers .= "INSERT printers
      (
        printer_series,
        printer_model,
        colored,
        vendor_id
      )
      SELECT      
        '$series',
        '$model',
        $colored, 
        (SELECT vendor_id FROM printer_vendors WHERE
          vendor_name = :vendor)       
      WHERE NOT EXISTS 
      (SELECT printer_series, printer_model FROM printers
      WHERE printer_series = '$series' AND printer_model = '$model') LIMIT 1;";

      $query_match .= "INSERT IGNORE printer_cartrige_match
      (
        printer_id,
        cartrige_id
      )
      VALUES 
      (
        (SELECT printer_id FROM printers 
        WHERE printer_series = '$series' AND printer_model = '$model'),
        (SELECT cartrige_id FROM cartriges
        WHERE series = :cartrige_series AND model = :cartrige_model)
      );";        
    }
  }

  $query_cartriges = $pdo->prepare($query_cartriges);
  echo $query_cartriges->execute
  ([
    'color_name'      => $_POST['color'], 
    'cartrige_series' => $_POST['series'], 
    'cartrige_model'  => $_POST['model'], 
    'price_1_pcs'     => $_POST['price_1_pcs'], 
    'price_2_pcs'     => $_POST['price_2_pcs'], 
    'price_5_pcs'     => $_POST['price_5_pcs'], 
    'price_in_office' => $_POST['price_in_office'],      
  ]);
  $query_cartriges->closeCursor();

  $query_printers = $pdo->prepare($query_printers);
  echo $query_printers->execute
  ([
    'vendor'          => $_POST['vendor'],      
  ]);
  $query_printers->closeCursor();

  $query_match = $pdo->prepare($query_match);
  echo $query_match->execute
  ([
    'cartrige_series' => $_POST['series'], 
    'cartrige_model'  => $_POST['model'],
  ]);
  


  header("Location: /admin_panel.php");
?>

<a href="/admin_panel.php">admin</a>
 
