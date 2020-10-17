<!-- Авторизация
Форма для приема файла
Проверка файла (в каждой строке 12 значений,
  если введен ИД картриджа, то проверяем наличие такого в базе: есть - заменяем заполненые значения, пустые пропускаем; нет - проверяем заполнение всех остальных полей(не пустые строки) 

  заполнено одно из наименоаний картриджа, заполнен вендор, заполнены 4 цены)

Подключение к базе данных -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
<?php 
  require_once 'assets/php/head.php';
 ?>
</head>
<body>

  <?php require_once 'assets/php/header.php'; ?>
  <table id="price-list">
    <thead>
      <tr><a class="btn" href="add_data_to_db.php">Insert data</a></tr>
      <tr>
        <th>ID</th>
        <th>Vendor</th>
        <th>Name</th>
        <th>Printers</th>
        <th>1pcs</th>
        <th>2pcs</th>
        <th>5pcs</th>
        <th>office</th>
        <th>Colored</th>
        <th>Ch</th>
        <th>Del</th>
      </tr>
    </thead>
  <?php 
    require_once 'assets/php/functions/connect_db.php'; 
    require_once 'assets/php/functions/p.php'; 
    $query = "
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
      <td><a href="delete_data.php?id=<?=$arr['cartrige_id']?>">Del</a></td>
    </tr>
  <?php  }
  ?>
  </table>
  
</body>
</html>
