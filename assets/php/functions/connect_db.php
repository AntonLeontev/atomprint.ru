<?php 
try {
  $pdo = new PDO('mysql:host=localhost;dbname=cartrige_refil', 
                 'test', 
                 'test',
                 [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch (PDOExtention $e) {
  echo "Неудалось устновить соединение с базой данных";
}
 
 ?>
