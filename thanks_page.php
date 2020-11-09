<?php 
include_once __DIR__ . '/assets/php/controllers/thanks_page_controller.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Спасибо за заявку!</title>
	<?php

	include_once 'assets/php/blocks/head.php';
	
	 ?>
</head>
<body>
<?php
	include_once __DIR__ . '/assets/php/blocks/header.php';
	include_once __DIR__ . '/assets/php/blocks/map-pop-up.php';	
?>

<div class="thanks_page">
	<h1 class="h1"><?=$message?></h1>
	<img src="/assets/img/call-print.jpg" alt="Заявка принята">
	<button class="tomain_btn" onclick="document.location='/index.php'">Перейти на главную</button>
</div>

</body>
</html>
