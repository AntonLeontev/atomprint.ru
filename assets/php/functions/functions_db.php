<?php 

function update_table_cartriges(PDO $pdo ,$id, $color, $series, $model, 
  $price_1_pcs, $price_2_pcs, $price_5_pcs, $price_in_office)
{
  $query = 
  "
    UPDATE cartriges
    SET color_id=(SELECT color_id FROM colors WHERE color_name = ?),
    series            = ?,
    model             = ?,
    price_1_pcs       = ?,
    price_2_pcs       = ?,
    price_5_pcs       = ?,
    price_in_office   = ?
    WHERE cartrige_id = ? 
  ;";

  $stmt = $pdo->prepare($query);
  $stmt->execute([
    $color,
    htmlspecialchars($series),
    htmlspecialchars($model),
    $price_1_pcs,
    $price_2_pcs,
    $price_5_pcs,
    $price_in_office,
    $id,
  ]);
  $stmt->closeCursor();
}

function get_printers_list(PDO $pdo, $cartrige_id)
{
  $query = 
  "
    SELECT printer_id FROM printer_cartrige_match
    WHERE cartrige_id=?
  ;";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$cartrige_id]);
  $data = $stmt->fetchAll(PDO::FETCH_NUM);
  return $data;
}

function delete_printers(PDO $pdo, $cartrige_id)
{
  $data = get_printers_list($pdo, $cartrige_id);
  foreach ($data as $v) {
    // Узнаем количество картриджей, которые подходят к каждому принтеру
    $query = 
    "
      SELECT COUNT(cartrige_id) FROM printer_cartrige_match
      WHERE printer_id=? LIMIT 2
    ;";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$v[0]]);
    $number_of_cartriges = $stmt->fetch(PDO::FETCH_NUM);
    $stmt->closeCursor();
    if ((int) $number_of_cartriges[0] > 1) {
      $query = 
      "
        DELETE FROM printer_cartrige_match
        WHERE cartrige_id=? AND printer_id=?
      ;";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$cartrige_id, $v[0]]);
    } else {
      $query = 
      "
        DELETE FROM printers
        WHERE printer_id=?
      ;";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$v[0]]);
    }
  }
}

// Получение данных из БД о принтерах для картриджа в виде 
  // ассоциативного массива
  function get_printers_series_and_models($cartrige_id, PDO $pdo)
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
  function get_printer_colored($cartrige_id, PDO $pdo) {
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
  function get_printer_vendor($cartrige_id, PDO $pdo) {
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



