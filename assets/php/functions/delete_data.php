<?php 
require_once 'connect_db.php';
require_once 'functions_db.php';

$id = $_GET['id'];

delete_printers($pdo, $id);

$query = "
  DELETE FROM cartriges
  WHERE cartrige_id=?
;";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

header("Location: /admin_panel.php?err=0");

?>
