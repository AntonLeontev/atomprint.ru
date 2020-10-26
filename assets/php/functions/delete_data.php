<?php 
require_once 'connect_db.php';
require_once 'functions_db.php';

$id = $_GET['id'];

delete_printers($pdo, $id);

// // Получаем список принтеров для данного картриджа
// $query = 
// "
//   SELECT printer_id FROM printer_cartrige_match
//   WHERE cartrige_id=?
// ;";
// $stmt = $pdo->prepare($query);
// $stmt->execute([$id]);
// $data = $stmt->fetchAll(PDO::FETCH_NUM); 

// // Удаляем связанные значения в таблице принтеров.
// foreach ($data as $v) {      
//   $query = 
//   "
//   DELETE FROM printers
//   WHERE printer_id=?
//   ;";
//   $stmt = $pdo->prepare($query);
//   $stmt->execute([$v[0]]);
// } 

$query = 
"
  DELETE FROM cartriges
  WHERE cartrige_id=?
;";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

header("Location: /admin_panel.php");

?>
