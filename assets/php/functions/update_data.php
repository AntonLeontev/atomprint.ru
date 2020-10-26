  <?php 
  require_once 'connect_db.php';
  require_once 'functions_db.php';
  require_once 'p.php';

  // Разбиваем по разделителю строку на разные серии принтеров и убираем пустые значения
  
  // Разбиваем по разделителю модели принтеров для каждой серии  
  
  // Объединяем серии и модели принтеров в ассоциативный массив
  $printers = check_accordance_series_models($_POST['printer_series'], $_POST['printer_model']);
  
  // Если данные введены верно, то получаем отдельно каждую модель 
  if ($printers) {
    foreach ($printers as &$v) {
      $v = explode('/', $v);
      array_walk($v, 'trim_value');
      delete_empty_values($v);
    }
  } else {
    header("Location: /change_data.php?id={$_POST['cartrige_id']}&err=1");
    exit();
  }


  // выполняем обновление в таблице картриджей
  update_table_cartriges(
    $pdo, 
    $_POST['cartrige_id'], 
    $_POST['color'], 
    $_POST['series'], 
    $_POST['model'], 
    $_POST['price_1_pcs'], 
    $_POST['price_2_pcs'], 
    $_POST['price_5_pcs'], 
    $_POST['price_in_office']
  );

    
  // Удаляем связанные значения в таблице принтеров.
  delete_printers($pdo, $_POST['cartrige_id']);

  // Готовим к вставке цвет принтера
  $colored = ($_POST['colored'] == '1') ? 1 : 0;

  //Проверяем наличие серии принтера и подготавливаем к вставке
  if (empty($_POST['printer_series'])) $printer_series = NULL; 

  //Создаем переменные для текста запросов
  $query_printers  = '';
  $query_match     = '';

  // Формируем SQL запрос
  
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

    $query_printers = $pdo->prepare($query_printers);
  $query_printers->execute
  ([
    'vendor'          => $_POST['vendor'],      
  ]);
  $query_printers->closeCursor();

  $query_match = $pdo->prepare($query_match);
  $query_match->execute
  ([
    'cartrige_series' => $_POST['series'], 
    'cartrige_model'  => $_POST['model'],
  ]);


// echo "<br><a href='/admin_panel.php'>Panel</a>";
  header("Location: /admin_panel.php");
 ?>
