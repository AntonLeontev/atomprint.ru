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
    for ($i=1; $data = fgetcsv($f, 0, ";"); $i++) {
      if ($i>1) {
        $j = 0; //счетчик ошибок
        $message = "Строка $i: ";
        if (empty($data[1])) {
          $message .= "Не указан производитель; "; $j++;
        }
        if (empty($data[2])) {
          $message .= "Не указан цвет картриджа; "; $j++;
        }
        if (empty($data[3]) && empty($data[4])) {
          $message .= "Не указана модель и серия картриджа; "; $j++;
        }
        if (empty($data[5])) {
          $message .= "Не указана цена за 1 картридж; "; $j++;
        } else {
          if (!preg_match("/^[0-9]{1,5}$/", $data[5])) {
            $message .= "Неправильный формат цены за 1 картридж; "; $j++;
          }          
        }
        if (empty($data[6])) {
          $message .= "Не указана цена за 2 картриджа; "; $j++;
        } else {
          if (!preg_match("/^[0-9]{1,5}$/", $data[6])) {
            $message .= "Неправильный формат цены за 2 картриджа; "; $j++;
          }          
        }
        if (empty($data[7])) {
          $message .= "Не указана цена за 5 картриджей; "; $j++;
        } else {
          if (!preg_match("/^[0-9]{1,5}$/", $data[7])) {
            $message .= "Неправильный формат цены за 5 картриджей; "; $j++;
          }          
        }
        if (empty($data[8])) {
          $message .= "Не указана цена за заправку в офисе; "; $j++;
        } else {
          if (!preg_match("/^[0-9]{1,5}$/", $data[8])) {
            $message .= "Неправильный формат цены за заправку в офисе; "; $j++;
          }          
        }
        if (empty($data[10])) {
          $message .= "Не указана модель принтера; "; $j++;
        }
        if (@!check_accordance_series_models($data[9], $data[10])) {
          $message .= "Количество серий принтеров не 
          соответстует количеству моделей принтеров; "; $j++;
        }
        if ($data[11] !== "1" && $data[11] !== "0") {
          $message .= "Не указана цветность принтера; "; $j++;
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
    "cartrige_id"     => htmlspecialchars($arr[0]),
    "vendor"          => htmlspecialchars($arr[1]),
    "color"           => htmlspecialchars($arr[2]),
    "series"          => htmlspecialchars($arr[3]),
    "model"           => htmlspecialchars($arr[4]),
    "price_1_pcs"     => htmlspecialchars($arr[5]),
    "price_2_pcs"     => htmlspecialchars($arr[6]),
    "price_5_pcs"     => htmlspecialchars($arr[7]),
    "price_in_office" => htmlspecialchars($arr[8]),
    "printer_series"  => htmlspecialchars($arr[9]),
    "printer_model"   => htmlspecialchars($arr[10]),
    "colored"         => htmlspecialchars($arr[11]),
  ];

  $my_curl = curl_init();
  curl_setopt_array($my_curl, array(
    CURLOPT_URL => 
    "http://{$_SERVER['HTTP_HOST']}/assets/php/functions/update_data.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTREDIR      => 7,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($data_arr)
  ));
  $response = curl_exec($my_curl);
  curl_close($my_curl);
  return $response;
}

// Отправка данных на добавление в базу
function send_post_add_data($arr)
{
  $data_arr=[
    "vendor"          => htmlspecialchars($arr[1]),
    "color"           => htmlspecialchars($arr[2]),
    "series"          => htmlspecialchars($arr[3]),
    "model"           => htmlspecialchars($arr[4]),
    "price_1_pcs"     => htmlspecialchars($arr[5]),
    "price_2_pcs"     => htmlspecialchars($arr[6]),
    "price_5_pcs"     => htmlspecialchars($arr[7]),
    "price_in_office" => htmlspecialchars($arr[8]),
    "printer_series"  => htmlspecialchars($arr[9]),
    "printer_model"   => htmlspecialchars($arr[10]),
    "colored"         => htmlspecialchars($arr[11]),
  ];

  $my_curl = curl_init();
  curl_setopt_array($my_curl, array(
    CURLOPT_URL => 
    "http://{$_SERVER['HTTP_HOST']}/assets/php/functions/add_data.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTREDIR      => 7,      
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($data_arr)
  ));
  $response = curl_exec($my_curl);
  curl_close($my_curl);
  return $response;
}

// Отправка данных на удаление
function send_post_delete_data($arr)
{
  $id = $arr[0];
  $my_curl = curl_init();
  curl_setopt_array($my_curl, array(
    CURLOPT_URL => 
    "http://{$_SERVER['HTTP_HOST']}/assets/php/functions/delete_data.php?id={$id}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,      
  ));
  $response = curl_exec($my_curl);
  curl_close($my_curl);
  return $response;
}

// Отправить файл на скачивание клиенту
function file_force_download($file, $filename) {
  // заставляем браузер показать окно сохранения файла
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Content-Disposition: attachment; filename=$filename");
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');

  // читаем файл и отправляем его пользователю
  while (!feof($file)) {
    echo fread($file, 2048);
  }
  fclose($file);
  exit;
}

function send_data(array $data)
{
  if (empty($data[0])) send_post_add_data($data);           
  else {
    if (trim($data[3]) === "-") send_post_delete_data($data);
    else send_post_update_data($data);
  }
}

function insert_changes($file)
{
  for ($i = 0; $data = fgetcsv($file, 0, ";"); $i++) { 
    if ($i > 0) send_data($data);
  }
}

function insert_changes_from_excel($file)
{
  for ($i = 0; $data = fgetcsv($file, 0, ";"); $i++) { 
    if ($i > 0) {
      mb_convert_encoding($data, "UTF-8", "CP1251");
      send_data($data);    
    }
  }
}


