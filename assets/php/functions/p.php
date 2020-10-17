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
 ?>
