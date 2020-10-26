<?php 

function update_table_cartriges($pdo ,$id, $color, $series, $model, $price_1_pcs, 
  $price_2_pcs, $price_5_pcs, $price_in_office)
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

function get_printers_list($pdo, $cartrige_id)
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

function delete_printers($pdo, $cartrige_id)
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




 ?>
