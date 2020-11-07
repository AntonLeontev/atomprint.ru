<?php 
if (isset($_GET['err'])) {
  $err = $_GET['err'];
  switch ($err) {
    case '0':
      $error_header  = 'Вы супер!!!';
      $error_message = 'Изменения внесены успешно';
      break;

    case '1':
      $error_header  = "Ошибка #$err Значение не добавлено";
      $error_message = 
      'Неверно расставлены разделители | в сериях и моделях принтеров';
      break;

    case '2':
      $error_header  = "Ошибка #$err Значение не добавлено";
      $error_message = '';
      break;

    case '3':
      $error_header  = "Ошибка #$err Файл не обработан";
      $error_message = 'Загружен файл неверного формата. Нужен формат .csv';
      break;     
  }
  include_once "assets/php/error.php";
}
  ?>

  

