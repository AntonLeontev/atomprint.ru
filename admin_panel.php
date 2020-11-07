<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
<?php 
  require_once 'assets/php/functions/upload_file.php';
  require_once 'assets/php/head.php';
 ?>
</head>
<body>

  <?php 
  require_once 'assets/php/header.php'; 
  require_once 'assets/php/admin_menu.php'; 
  require_once 'assets/php/upload_form.php';
  require_once 'assets/php/functions/error_script.php';
  ?>
  
  <table id="price-list">
    <thead>
      <tr>
        <th>ID</th>
        <th>Производитель</th>
        <th>Картридж</th>
        <th>Принтеры</th>
        <th>1 шт</th>
        <th>2 шт</th>
        <th>5 шт</th>
        <th>В офисе</th>
        <th>Цветной?</th>
        <th>Ch</th>
        <th>Del</th>
      </tr>
    </thead>
  <?php 
    $query = 
    "
      SELECT C.cartrige_id, C.series, C.model, C.price_1_pcs, C.price_2_pcs, C.price_5_pcs, C.price_in_office, colors.image_path, colors.color_name 
      FROM cartriges AS C
      JOIN colors ON C.color_id=colors.color_id
      ORDER BY cartrige_id DESC;
    ";
    $stmt = $pdo->query($query);
    while ($arr = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $printer_name = get_printers_series_and_models($arr['cartrige_id'], $pdo);
      $arr['printers'] = '';
      foreach ($printer_name as $key => $value) {
        $arr['printers'] .= "<span>$key </span> $value</br>";
      }
      $vendor  = get_printer_vendor($arr['cartrige_id'], $pdo); 
      $colored = get_printer_colored($arr['cartrige_id'], $pdo);
      ?>
    <tr>
      <td><?=$arr['cartrige_id']?></td>
      <td><?=$vendor?></td>
      <td><img width="12px" src="<?=$arr['image_path']?>" title="<?=$arr['color_name']?>" alt="<?=$arr['color_name']?>"><?=$arr['series'].'<span>'.$arr['model'].'</span>'?></td>
      <td><?=$arr['printers']?></td>
      <td><?=$arr['price_1_pcs']?></td>
      <td><?=$arr['price_2_pcs']?></td>
      <td><?=$arr['price_5_pcs']?></td>
      <td><?=$arr['price_in_office']?></td>
      <td><?=$colored?></td>
      <td><a href="change_data.php?id=<?=$arr['cartrige_id']?>">Ch</a></td>
      <td><a href="/assets/php/functions/delete_data.php?id=<?=$arr['cartrige_id']?>">Del</a></td>
    </tr>
  <?php  }
  ?>
  </table>
  
</body>
<script src="assets/js/admin_scripts.js"></script>
</html>
