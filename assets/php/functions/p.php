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
          if (!preg_match("/^[0-9]+$/", $data[5])) {
            $message .= "неправильный формат цены за 1 картридж; "; $j++;
          }
          if (empty($data[6])) {
            $message .= "не указана цена за 2 картриджа; "; $j++;
          }
          if (!preg_match("/^[0-9]+$/", $data[6])) {
            $message .= "неправильный формат цены за 2 картриджа; "; $j++;
          }
          if (empty($data[7])) {
            $message .= "не указана цена за 5 картриджей; "; $j++;
          }
          if (!preg_match("/^[0-9]+$/", $data[7])) {
            $message .= "неправильный формат цены за 5 картриджей; "; $j++;
          }
          if (empty($data[8])) {
            $message .= "не указана цена за заправку в офисе; "; $j++;
          }
          if (!preg_match("/^[0-9]+$/", $data[8])) {
            $message .= "неправильный формат цены за заправку в офисе; "; $j++;
          }
          if (empty($data[10])) {
            $message .= "не указана модель принтера; "; $j++;
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
  }
 ?>
