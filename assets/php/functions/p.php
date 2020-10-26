<?php 
// Отладочная функция
  function p($obj)
  {
    echo 
      "<pre>",
        htmlspecialchars(pGet($obj)),
      "</pre>";
  }

  function pGet($obj, $leftSp = '')
  {
    if (is_array($obj)) {
      $type = "Array[" . count($obj) . ']';
    } elseif (is_object($obj)) {
      $type = 'Object ' . get_class($obj);
      if (method_exists($obj, '__toString')) $type .= "\n$obj";
      // else $type .= "\n" . pGet(get_object_vars($obj));
    } elseif (gettype($obj) == 'boolean') {
      return ($obj) ? '(bool)true' : '(bool)false' ;
    } elseif (gettype($obj) == 'integer' || gettype($obj) == "double") {
      return "$obj";
    } else {
      return "\"$obj\"";
    }

    $buf = $type;
    $leftSp .= '  ';

    foreach ($obj as $k => $v) {
      if ($k === "GLOBALS") continue;
      if (gettype($k) == 'string') {
        $buf .= "\n$leftSp\"$k\" => " . pGet($v, $leftSp);
      } else {
        $buf .= "\n$leftSp$k => " . pGet($v, $leftSp);
      } 
    }
    
    return $buf;
  }

  // Обрезка пробелов во всех стороках массива
  function trim_value(&$value)
  {
    $value = trim($value);
  }


  // Удаление пустых строк из массива
  function delete_empty_values(&$arr)
  {
    for ($i = array_search('', $arr); 
         $i !== false; 
         $i = array_search('', $arr)) { 
      array_splice($arr, $i, 1);
    }
  }

  // Получение данных из БД о принтерах для картриджа в виде 
  // ассоциативного массива
  function get_printers_series_and_models($cartrige_id, $pdo)
  {
    // готовим запрос в БД
    $query = "
      SELECT printer_series
      FROM printer_cartrige_match AS mtc, printers AS P
      WHERE cartrige_id=? AND mtc.printer_id=P.printer_id
      ORDER BY printer_series;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cartrige_id]);

    //Собираем все серии принтеров в массив
    $printer_series = []; 
    while ($printer_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $printer_series[] = $printer_data['printer_series'];
    }

    // содаем результирующий массив и собираем в нем массивы с ключем-серией принтера (все лючи уникальны)
    $printer_series_and_models = [];
    foreach (array_flip($printer_series) as $key => $value) {
      $printer_series_and_models[$key] = [];
    }

    // Создаем запрос для получения моделей принтеров из БД
    $query = "
      SELECT printer_model
      FROM printer_cartrige_match AS mtc, printers AS P
      WHERE cartrige_id=? AND mtc.printer_id=P.printer_id AND P.printer_series=?;
    ";

    // Для каждой серии получаем модели и сохраняем их в результирующий массив
    foreach (array_unique($printer_series) as $ser) {
      $stmp = $pdo->prepare($query);
      $stmp->execute([$cartrige_id, $ser]);
      while ($printer_models = $stmp->fetch(PDO::FETCH_ASSOC)) {
        $printer_series_and_models["$ser"][] = $printer_models['printer_model'];
      }
    }

    // Объединяем модели в строку через разделитель
    foreach ($printer_series_and_models as $k => $v) {
      $printer_series_and_models[$k] = join(" / ", $v);
    }

    return $printer_series_and_models;
  }

  // Получение цветности принтера
  function get_printer_colored($cartrige_id, $pdo) {
    // готовим запрос в БД
    $query = "
      SELECT colored
      FROM printer_cartrige_match AS mtc, printers AS P
      WHERE cartrige_id=? AND mtc.printer_id=P.printer_id
      LIMIT 1;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cartrige_id]);
    $result = $stmt->fetch();
    return $result[0];
  }

  // Получение производителя принтера
  function get_printer_vendor($cartrige_id, $pdo) {
    // готовим запрос в БД
    $query = "
      SELECT vendor_name
      FROM printer_cartrige_match AS mtc, printers AS P, printer_vendors AS V
      WHERE cartrige_id=? AND mtc.printer_id=P.printer_id AND V.vendor_id=P.vendor_id
      LIMIT 1;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cartrige_id]);
    $result = $stmt->fetch();
    return $result[0];
  }

  //Проверка имени файла на содержание только английских символов, цифр и ./-_
  function check_file_uploaded_name ($filename)
  {
      (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
  }


  // Проверка что имя файла короче 225 символов
  function check_file_uploaded_length ($filename)
  {
      return (bool) ((mb_strlen($filename,"UTF-8") > 225) ? true : false);
  }

  function check_file_mistakes($f)
  {
    if ($f) {
      $mistakes = [];
      for ($i=1; $data = fgetcsv($f); $i++) {
        if ($i>1) {
          $j = 0; //счетчик ошибок
          $message = "Ошибка в строке $i: ";
          if (empty($data[1])) {
            $message .= "не указан производитель; "; $j++;
          }
          if (empty($data[2])) {
            $message .= "не указан цвет картриджа; "; $j++;
          }
          if (empty($data[3]) && empty($data[4])) {
            $message .= "не указана модель и серия картриджа; "; $j++;
          }
          if (empty($data[5])) {
            $message .= "не указана цена за 1 картридж; "; $j++;
          }
          if (!preg_match("/^[0-9]{1,5}$/", $data[5])) {
            $message .= "неправильный формат цены за 1 картридж; "; $j++;
          }
          if (empty($data[6])) {
            $message .= "не указана цена за 2 картриджа; "; $j++;
          }
          if (!preg_match("/^[0-9]{1,5}$/", $data[6])) {
            $message .= "неправильный формат цены за 2 картриджа; "; $j++;
          }
          if (empty($data[7])) {
            $message .= "не указана цена за 5 картриджей; "; $j++;
          }
          if (!preg_match("/^[0-9]{1,5}$/", $data[7])) {
            $message .= "неправильный формат цены за 5 картриджей; "; $j++;
          }
          if (empty($data[8])) {
            $message .= "не указана цена за заправку в офисе; "; $j++;
          }
          if (!preg_match("/^[0-9]{1,5}$/", $data[8])) {
            $message .= "неправильный формат цены за заправку в офисе; "; $j++;
          }
          if (empty($data[10])) {
            $message .= "не указана модель принтера; "; $j++;
          }
          if (!check_accordance_series_models($data[9], $data[10])) {
            $message .= "количество серий принтеров не 
            соответстует количеству моделей принтеров; "; $j++;
          }
          if ($data[11] !== "1" && $data[11] !== "0") {
            $message .= "не указана цветность принтера; "; $j++;
          }
          if ($j>0) {
            $mistakes[] = $message;
          }          
        }
      }
      if (empty($mistakes)) {
        return false;
      } else return $mistakes;
    }
    fclose($f);
  }

  // Разбиваем по разделителю строку на разные серии принтеров и убираем пустые значения
  function split_printer_series($printer_series)
  {
    $result = explode('|', $printer_series);
    array_walk($result, 'trim_value'); 
    if (count($result)>=1 && !empty($result[0])) {
      delete_empty_values($result);      
    } else $result[0] = '';
    return $result;
  }

  // Разбиваем по разделителю модели принтеров для каждой серии 
  function split_printer_models($printer_models)
  {
    $result = explode('|', $printer_models);
    array_walk($result, 'trim_value'); 
    delete_empty_values($result);
    return $result;
  }

  // Объединяем серии и модели принтеров в ассоциативный массив. Если 
  // количество разделителей для серий и моделей разное - вернет false
  function check_accordance_series_models($series, $models)
  {
    $printer_series = split_printer_series($series);  
    $printer_model = split_printer_models($models);  
    $printers = array_combine($printer_series, $printer_model);
    return $printers;
  }

  // Отправка данных на обновление по ИД картриджа
  function send_post_update_data($arr)
  {  
    $data_arr=[
      "cartrige_id"     => $arr[0],
      "vendor"          => $arr[1],
      "color"           => $arr[2],
      "series"          => $arr[3],
      "model"           => $arr[4],
      "price_1_pcs"     => $arr[5],
      "price_2_pcs"     => $arr[6],
      "price_5_pcs"     => $arr[7],
      "price_in_office" => $arr[8],
      "printer_series"  => $arr[9],
      "printer_model"   => $arr[10],
      "colored"         => $arr[11]
    ];

    $my_curl = curl_init();
    curl_setopt_array($my_curl, array(
      CURLOPT_URL => 'http://atomprint.ru/assets/php/functions/update_data.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query($data_arr)
    ));
    $response = curl_exec($my_curl);
    curl_close($my_curl);

  }

  // Отправка данных на добавление в базу
  function send_post_add_data($arr)
  {
    $data_arr=[
      "vendor"          => $arr[1],
      "color"           => $arr[2],
      "series"          => $arr[3],
      "model"           => $arr[4],
      "price_1_pcs"     => $arr[5],
      "price_2_pcs"     => $arr[6],
      "price_5_pcs"     => $arr[7],
      "price_in_office" => $arr[8],
      "printer_series"  => $arr[9],
      "printer_model"   => $arr[10],
      "colored"         => $arr[11]
    ];

    $my_curl = curl_init();
    curl_setopt_array($my_curl, array(
      CURLOPT_URL => 'http://atomprint.ru/assets/php/functions/add_data.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query($data_arr)
    ));
    $response = curl_exec($my_curl);
    curl_close($my_curl);
  }

  // Отправка данных на удаление
  function send_post_delete_data($arr)
  {
    $id = $arr[0];
    p($id);
    $my_curl = curl_init();
    curl_setopt_array($my_curl, array(
      CURLOPT_URL => 
      "http://atomprint.ru/assets/php/functions/delete_data.php?id={$id}",
      CURLOPT_RETURNTRANSFER => true,      
    ));
    $response = curl_exec($my_curl);
    curl_close($my_curl);
  }

  // Отправить файл на скачивание клиенту
  function file_force_download($file) {
  // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
  // если этого не сделать файл будет читаться в память полностью!
  if (ob_get_level()) {
    ob_end_clean();
  }
  // заставляем браузер показать окно сохранения файла
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename=db.csv');
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  // header('Content-Length: ' . filesize($file));

  // читаем файл и отправляем его пользователю
  while (!feof($file)) {
    print fread($file, 1024);
  }
  fclose($file);
  exit;
}


 ?>
