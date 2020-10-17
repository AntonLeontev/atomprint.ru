 <?php 
  require_once 'assets/php/functions/connect_db.php'; 
  $query = "SELECT color_name FROM colors";
  $colors = $pdo->query($query);
  $colors = $colors->fetchAll(PDO::FETCH_ASSOC);

  $query = "SELECT vendor_name FROM printer_vendors";
  $vendors = $pdo->query($query);
  $vendors = $vendors->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add new cartrige</title>
  <?php 
    require_once 'assets/php/head.php';
  ?>
</head>
<body>
  <form class="add_data_form" action="assets/php/functions/add_data.php" method="POST">
    <table class="data_table">
      <caption>Добавление новой записи в базу данных</caption>
      <thead>
        <tr>
          <th colspan="8">Картридж</th>
          <th class="printer" colspan="3">Принтер</th>
        </tr>
        <tr class="thead_titles">
          <th title="Производитель">Производи<wbr>тель</th>
          <th>Цвет</th>
          <th>Серия</th>
          <th>Модель</th>
          <th>Выезд на 1 шт*</th>
          <th>Выезд на 2 шт*</th>
          <th>Выезд на 5 шт*</th>
          <th>Цена в офисе*</th>
          <th class="printer">Серия</th>
          <th class="printer">Модель</th>
          <th class="printer">Цветной?</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="admin_td_cell">
            <select class="select" name="vendor" value='vendor' required>
              <option value='vendor' disabled="" selected=""></option>
              <?php foreach ($vendors as $vendor) { ?>
              <?="<option value={$vendor['vendor_name']}>{$vendor['vendor_name']}</option>"?>      
              <?php } ?>
            </select>
          </td>
          <td class="admin_td_cell">
            <select name="color" value='color' required>
              <option value='vendor' disabled="" selected="">Color</option>
              <?php foreach ($colors as $color) { ?>
              <?="<option value=".$color['color_name'].">{$color['color_name']}</option>"?>      
              <?php } ?>
            </select>            
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="series" placeholder="series">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="model" placeholder="model">
            </div>  
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_1_pcs" placeholder="price_1_pcs" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_2_pcs" placeholder="price_2_pcs" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_5_pcs" placeholder="price_5_pcs" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_in_office" placeholder="price_in_office" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="printer_series" placeholder="printer_series">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="printer_model" placeholder="printer_model" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <select class="select" name="colored">
              <option value="0" selected="">Черно-белый</option>
              <option value="1">Цветной</option>    
            </select>
          </td>
        </tr>
        <tr></tr>
      </tbody>
    </table>
    
    <input type="submit" value="Add">
  </form>
  <?php 
    if (isset($_GET['err']) && $_GET['err'] == '1') {
      echo "Ошибка! Значение не добавлено.<br>Не добавлены модели принтеров для каждой серии принтеров";
    } else if (isset($_GET['err']) && $_GET['err'] == '2') {
      echo "Ошибка! Значение не добавлено.<br>Заполнены не все обязательные поля";
    }
   ?>
</body>
</html>
