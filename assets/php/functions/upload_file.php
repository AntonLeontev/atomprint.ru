<?php 
require_once 'p.php';
require_once 'connect_db.php';

echo("<br>");
echo "<a href=\"../../../admin_panel.php\">Panel</a>";

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
    } else echo "Ошибок в файле нет";
  } else {
    // die('Передан не CSV файл');
    header("Location: /admin_panel.php?err=3");
    exit();
  } 
} else echo "file not open";
  




?>
