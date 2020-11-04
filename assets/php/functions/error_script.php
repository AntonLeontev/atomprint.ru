<?php 
if (isset($_GET['err'])) {
  $err = $_GET['err'];
  switch ($err) {
    case '0':
      $header  = 'Вы супер!!!';
      $message = 'Изменения внесены успешно';
      break;

    case '1':
      $header  = "Ошибка #$err Значение не добавлено";
      $message = 'Неверно расставлены разделители | в сериях и моделях принтеров';
      break;

    case '2':
      $header  = "Ошибка #$err Значение не добавлено";
      $message = '';
      break;

    case '3':
      $header  = "Ошибка #$err Файл не обработан";
      $message = 'Загружен файл неверного формата. Нужен формат .csv';
      break;     
  }
  include_once "assets/php/error.php";
}
  ?>

  

