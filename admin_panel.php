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
        <th>Name</th>
        <th>Printers</th>
        <th>1pcs</th>
        <th>2pcs</th>
        <th>5pcs</th>
        <th>office</th>
        <th>Ch</th>
      </tr>
    </thead>
  <?php 
    require_once 'assets/php/functions/connect_db.php'; 
    $query = "SELECT * FROM cartriges
            ORDER BY cartrige_id DESC";
    $obj = $pdo->query($query);
    while ($arr = $obj->fetch(PDO::FETCH_ASSOC)) {
      ?>
    <tr>
      <td><?=$arr['cartrige_id']?></td>
      <td><?=$arr['color_id'].$arr['series'].
      '<span>'.$arr['model'].'</span>'?></td>
      <td>
        
      </td>
      <td><?=$arr['price_1_pcs']?></td>
      <td><?=$arr['price_2_pcs']?></td>
      <td><?=$arr['price_5_pcs']?></td>
      <td><?=$arr['price_in_office']?></td>
      <td><a href="change_data.php?id=<?=$arr['cartrige_id']?>">Ch</a></td>
    </tr>
  <?php  }

  ?>
  </table>


  
</body>
</html>