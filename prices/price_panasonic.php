<?php
include_once '../assets/php/blocks/cookies.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Заправка картриджей Panasonic в Екатеринбурге | Атомпринт</title>
  <meta name="description" content="Профессиональная заправка картриджей Panasonic в Екатеринбурге. Цены на заправку картриджей Panasonic. Заправка картриджей Panasonic с выездом.">
  <meta name="keywords" content="атомпринт,заправка картриджей,Panasonic">
  <?php
    include_once '../assets/php/blocks/head.php'
   ?>

</head>
<body>
  <?php
    include_once '../assets/php/blocks/header.php';
    include_once '../assets/php/blocks/printers_menu.php';
  ?>
  <h1 class="h1_price">Стоимость заправки картриджей Panasonic</h1>
  <div id="price">                       
            


<table id="price-list">
  <thead>
    <tr>
      <th class="first_col" scope="col" rowspan="2">Номер картриджа</th>
      <th class="models" scope="col" rowspan="2">Модель принтера</th>
      <th scope="col" colspan="3">Заправка с выездом</th>
      <th class="fixed_col" scope="col" rowspan="2">Заправка <br> у нас в офисе</th>
    </tr>
    <tr>
      <th class="fixed_col" scope="col">1 шт</th>
      <th class="fixed_col" scope="col">oт 2 шт</th>
      <th class="fixed_col" scope="col">oт 5 шт</th>
    </tr>
  </thead>
  <tbody>                
    <tr>
      <td class="color">
        <div class="bk">KX-FAT<span>400A7</span></div>
        <div class="bk">KX-FAT<span>410A7</span></div>
      </td>
      <td>KX-MB 1500 / 1520 / 1530</td>
      <td>900</td>
      <td>700</td>
      <td>600</td>
      <td>500</td>
    </tr>
    <tr>
      <td class="color">
        <div class="bk">KX-FAT<span>421A7</span></div>
        <div class="bk">KX-FAT<span>430A7</span></div>
        <div class="bk">KX-FAT<span>431A7</span></div>
      </td>
      <td>KX-MB 2230 / 2270 / 2510 / 2540 / 2571</td>
      <td>900</td>
      <td>700</td>
      <td>600</td>
      <td>500</td>
    </tr>                 
  </tbody>
</table>   



  <?php
    include_once '../assets/php/blocks/footer.php';
    include_once '../assets/php/blocks/map-pop-up.php';
    if (!isset($_COOKIE["accept_notice"])) {
      include_once '../assets/php/blocks/notice.php';
     }
  ?>

</body>
<script type="text/javascript" src="/assets/js/scripts_body.js"></script>
