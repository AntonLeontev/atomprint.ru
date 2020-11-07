<?php 
require_once 'assets/php/functions/p.php';
require_once 'assets/php/functions/connect_db.php';
require_once 'assets/php/functions/functions_db.php';

// Проверяем тип файла и имя на соответствие
if (!empty($_FILES) && $_FILES['file']['error'][0] == 0) {
  if ($_FILES['file']['type'][0] == "text/csv") {
    $filename = $_FILES['file']['tmp_name'][0].'/'.$_FILES['file']['name'][0];

    // Если все хорошо, перемещаем файл на сервер и проверяем содержимое
    $uploads_dir = "files";
    $tmp_name = $_FILES["file"]["tmp_name"][0];
    $name = basename($_FILES["file"]["name"][0]);
    move_uploaded_file($tmp_name, "$uploads_dir/$name");

    $f = fopen("$uploads_dir/$name", 'r');
    if ($mistakes = check_file_mistakes($f)) {
      $error_header  = 'Ошибки в заполнении файла:';
      $error_message = join("<br>", $mistakes);
      include_once "assets/php/error.php";
    } else {
      // Если ошибок в файле нет, циклично записываем данные
      $f = fopen("$uploads_dir/$name", 'r');

      if (isset($_POST['excel'])) insert_changes_from_excel($f);
      else insert_changes($f);

      fclose($f);
      header("Location: /admin_panel.php?err=0");
    }
    unlink(realpath("$uploads_dir/$name"));
  } else {
    // die('Передан не CSV файл');
    header("Location: /admin_panel.php?err=3");
    exit();
  } 
}

