<?php 
  require_once 'assets/php/functions/connect_db.php';
  require_once 'assets/php/functions/p.php';
  require_once 'assets/php/functions/functions_db.php';

  // Получаем список цветов картриджей
  $query = "SELECT color_name, image_path FROM colors";
  $colors = $pdo->query($query);
  $colors = $colors->fetchAll(PDO::FETCH_ASSOC);

  // Получаем список производителей
  $query = "SELECT vendor_name FROM printer_vendors";
  $vendors = $pdo->query($query);
  $vendors = $vendors->fetchAll(PDO::FETCH_ASSOC);

  // Получаем данные по ИД картриджа
  $id = $_GET['id'];

  $query = "
  SELECT series, model, price_1_pcs, price_2_pcs, price_5_pcs, price_in_office, color_name 
  FROM cartriges AS cart, colors AS col 
  WHERE cartrige_id=? AND cart.color_id=col.color_id;";
  $cartrige_data = $pdo->prepare($query);
  $cartrige_data->execute([$id]);
  $cartrige_data = $cartrige_data->fetch(PDO::FETCH_ASSOC);

  // Получаем производителя серию, модели и цветность принтеров для искомого картриджа 
  $printer_series_and_models = get_printers_series_and_models($id, $pdo);
  $vendor  = get_printer_vendor($id, $pdo); 
  $colored = get_printer_colored($id, $pdo);

  $printer_models = join(" | ", $printer_series_and_models);
  $printer_series = join(" | ", array_keys($printer_series_and_models));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update cartrige data</title>
  <?php 
    require_once 'assets/php/head.php';
  ?>
</head>
<body>
  <form class="add_data_form" action="assets/php/functions/update_data.php" method="POST">
    <table class="data_table">
      <caption>Обновление значения картриджа с ID <?=$id?></caption>
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
              <?php 
              foreach ($vendors as $v) {
                echo "<option value=\"{$v['vendor_name']}\""; 
                if ($v['vendor_name'] == $vendor) 
                  echo " selected"
                ;
                echo ">{$v['vendor_name']}</option>";      
              } 
              ?>
            </select>
          </td>
          <td class="admin_td_cell">
            <select name="color" value='color' required>
              <option value='vendor' disabled="" selected="">Color</option>
              <?php 
              foreach ($colors as $color) { 
                echo "<option value=\"{$color['color_name']}\"";
                if ($color['color_name'] == $cartrige_data['color_name'])
                  echo " selected";
                echo ">{$color['color_name']}</option>";      
              } ?>
            </select>            
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="series" placeholder="series" value="<?=$cartrige_data['series']?>">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="model" placeholder="model" value="<?=$cartrige_data['model']?>">
            </div>  
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_1_pcs" placeholder="price_1_pcs" value="<?=$cartrige_data['price_1_pcs']?>" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_2_pcs" placeholder="price_2_pcs" value="<?=$cartrige_data['price_2_pcs']?>" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_5_pcs" placeholder="price_5_pcs" value="<?=$cartrige_data['price_5_pcs']?>" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="number" name="price_in_office" placeholder="price_in_office" value="<?=$cartrige_data['price_in_office']?>" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="printer_series" placeholder="printer_series" value="<?=$printer_series?>">
            </div>
          </td>
          <td class="admin_td_cell">
            <div class="input_wrap">
              <input type="text" name="printer_model" placeholder="printer_model" value="<?=$printer_models?>" required="">
            </div>
          </td>
          <td class="admin_td_cell">
            <select class="select" name="colored">
              <option value="0">Черно-белый</option>
              <option value="1" 
              <?php if ($colored == '1') echo " selected"; ?>
              >Цветной</option>    
            </select>
          </td>
        </tr>
        <tr></tr>
      </tbody>
    </table>

    <input type="text" name="cartrige_id" hidden value="<?=$id?>">
    <input type="submit" value="Сохранить">
  </form>
</body>
</html>
 
