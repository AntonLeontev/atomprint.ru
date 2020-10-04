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
 ?>