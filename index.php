<?php
include_once 'assets/php/cookies.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Ремонт принтеров и заправка картриджей в Екатеринбурге | Атомпринт</title>
	<meta name="description" content="Атомпринт - сервисная компания по ремонту принтеров и любой оргтехники. Занимаемся заправкой картриджей, обслуживанием и чисткой МФУ. Наша задача - сделать так, чтобы у вас в офисе все хорошо и правильно печатало">
	<meta name="keywords" content="атомпринт,заправка картриджей,ремонт принтеров,ремонт мфу">
	<?php
		include_once 'assets/php/head.php'
	 ?>

</head>
<body>
	<?php
		include_once 'assets/php/header.php';
		include_once 'assets/php/printers_menu.php';
	?>
	<div class="main_bgrd">
		<div class="main_conteiner">
			<h1>Ремонт и обслуживание оргтехники</h1>
			<h2>Сервисный центр Атомпринт</h2>
			<a class="refill_img" href="zapravka.php">
				<img class="refill_img" src="assets/img/zapravka.jpg" alt="Заправка картриджей Атомпринт">
			</a>
			<a class="main_btn_refill" href="zapravka.php">Заправить картридж</a>
			<a class="repair_img" href="remont.php">
				<img src="assets/img/repair.jpg" alt="Ремонт принтеров Атомпринт">
			</a>
			<a class="main_btn_repair" href="remont.php">Ремонт МФУ и принтеров</a>
			<a class="buy_img" href="kupit.php">
				<img class="buy_img" src="assets/img/cartrige.jpg" alt="Купить картридж Атомпринт">
			</a>
			<a class="main_btn_buy" href="kupit.php">Купить новый картридж</a>
		</div>
	</div>
	<?php
		include_once 'assets/php/footer.php';
		include_once 'assets/php/map-pop-up.php';
		if (!isset($_COOKIE["accept_notice"])) {
		 	include_once 'assets/php/notice.php';
		 }
	?>

</body>
<script type="text/javascript" src="/assets/js/scripts_body.js"></script>