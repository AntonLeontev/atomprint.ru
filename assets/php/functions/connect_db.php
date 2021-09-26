<?php 
try {
  $pdo = new PDO('mysql:host=localhost;
  								dbname=t91265r5_cart', 
                 't91265r5_cart', 
                 'Atom0102',
                 [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
}
catch (PDOExtention $e) {
  echo "Неудалось устновить соединение с базой данных $e";
}
 
 ?>
