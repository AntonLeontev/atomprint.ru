<?php 
  require_once 'connect_db.php';
  if (!empty($_POST['series']) || !empty($_POST['model'])) {
    $query = "INSERT cartriges (color_id, series, model, price_1_pcs, price_2_pcs, price_5_pcs, price_in_office) VALUES 
    (
      (SELECT color_id FROM colors WHERE color_name = ?),
      ?,
      ?,
      ?,
      ?,
      ?,
      ?      
    );";
    $smtp = $pdo->prepare($query);
    $smtp->execute([$_POST['color'], $_POST['series'], $_POST['model'], $_POST['price_1_pcs'], $_POST['price_2_pcs'], $_POST['price_5_pcs'], $_POST['price_in_office']]);
  }
  header("Location: /admin_panel.php");
 ?>

 
