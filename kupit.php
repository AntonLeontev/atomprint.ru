<?php
include_once 'assets/php/blocks/cookies.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Купить картриджи для принтеров | Атомпринт</title>
	<meta name="description" content="Купить картриджи для принтеров HP, Canon, Xerox, Brother, Kyocera, Samsung">
	<meta name="keywords" content="атомпринт,доставка картриджей,купить картридж для принтера,купить картридж для МФУ">
	<?php
		include_once 'assets/php/blocks/head.php'
	 ?>
</head>
<body>
	<?php
		include_once 'assets/php/blocks/header.php'
	?>
	<div class="first_screen">
		<h1 class="h1">Картриджи для принтеров</h1>
		<h2 class="h2">Доставляем в офис по Екатеринбургу</h2>
		<div class="text_first_screen">
			<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto" height="22" width="auto">Стоимость картриджа от 550 р.</p>
			<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto">Сами подберем подходящий картридж</p>
			<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto">67% заказов доставляем в тот же день
			Остальные - на следующий.</p>
			<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto">Возможен расчет с НДС и без НДС</p>
		</div>
		<div class="vendors">
			<img id="hp" src="/assets/img/vendors/HP.png" width="100%" height="auto" alt="HP">
			<img id="kyo" src="/assets/img/vendors/Kyo.jpg" width="100%" height="auto" alt="Kyocera">
			<img id="bro" src="/assets/img/vendors/brother.png" width="100%" height="auto" alt="Brother">
			<img id="panas" src="/assets/img/vendors/panasonic.png" width="100%" height="auto" alt="Panasonic">
			<img id="can" src="/assets/img/vendors/canon.jpg" width="100%" height="auto" alt="Canon">
			<img id="xer" src="/assets/img/vendors/Xerox.png" width="100%" height="auto" alt="Xerox">
			<img id="sams" src="/assets/img/vendors/sams.jpeg" width="100%" height="auto" alt="Samsung">
			<img id="pant" src="/assets/img/vendors/pantum.png" width="100%" height="auto" alt="Pantum">
		</div>
	</div>
	<?php
        include_once __DIR__ . '/assets/php/blocks/callback.php';
        include_once __DIR__ . '/assets/php/blocks/feedback.php';
        include_once __DIR__ . '/assets/php/blocks/slider.php';
        include_once __DIR__ . '/assets/php/blocks/footer.php';
        include_once __DIR__ . '/assets/php/blocks/footer.php';
		include_once __DIR__ . '/assets/php/blocks/map-pop-up.php';
        
        if (!isset($_COOKIE["accept_notice"])) {
		 	include_once 'assets/php/blocks/notice.php';
  		}
    ?>
</body>
<script type="text/javascript" src="/assets/js/scripts_body.js"></script>
</html>
