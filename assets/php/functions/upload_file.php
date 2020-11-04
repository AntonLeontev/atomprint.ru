<?php 
require_once 'p.php';
require_once 'connect_db.php';
require_once 'functions_db.php';


if (!empty($_FILES) && $_FILES['file']['error'][0] == 0) {
  if ($_FILES['file']['type'][0] == "text/csv") {
    $filename = $_FILES['file']['tmp_name'][0].'/'.$_FILES['file']['name'][0];

    $uploads_dir = "../../../files/";
    $tmp_name = $_FILES["file"]["tmp_name"][0];
    // basename() может предотвратить атаку на файловую систему;
    // может быть целесообразным дополнительно проверить имя файла
    $name = basename($_FILES["file"]["name"][0]);
    move_uploaded_file($tmp_name, "$uploads_dir/$name");

    $f = fopen("$uploads_dir/$name", 'r');
    if ($m = check_file_mistakes($f)) {
      p($m);
    } else {
      // Если оибок в файле нет циклично записываем данные
      $f = fopen("$uploads_dir/$name", 'r');

      for ($i=0; $data = fgetcsv($f, 0, ";"); $i++) { 
        if ($i>0) {
          if (empty($data[0])) send_post_add_data($data);             
          else {
            if (trim($data[3]) === "-") send_post_delete_data($data);
            else send_post_update_data($data);
          }
        }
      }
      fclose($f);
      unlink(realpath("$uploads_dir/$name"));
      header("Location: /admin_panel.php?err=0");
    }
  } else {
    // die('Передан не CSV файл');
    header("Location: /admin_panel.php?err=3");
    exit();
  } 
} else echo "file not open";
  




?>
