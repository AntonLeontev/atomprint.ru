<?php
include_once 'assets/php/blocks/cookies.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Заправка картриджей в Екатеринбурге | Атомпринт</title>
	<meta name="description" content="Атомпринт - сервисная компания по ремонту принтеров и любой оргтехники. Занимаемся заправкой картриджей, обслуживанием и чисткой МФУ. Наша задача - сделать так, чтобы у вас в офисе все хорошо и правильно печатало">
	<meta name="keywords" content="заправка картриджей,ремонт принтеров,ремонт мфу">
	<?php
		include_once __DIR__ . '/assets/php/blocks/head.php'
	 ?>
</head>
<body>
	<?php
		include_once __DIR__ . '/assets/php/blocks/header.php';
		include_once 'assets/php/blocks/printers_menu.php';
	 ?>

	<div class="content">

		<div class="first_screen">
			<h1 class="h1">Заправка картриджей</h1>
			<h2 class="h2">с выездом курьера по Екатеринбургу</h2>
			<div class="text_first_screen">
				<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto" height="22" width="auto">Стоимость заправки от 250 р.</p>
				<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto">Адекватная и понятная консультация</p>
				<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto">Привозим сразу заправленый картридж</p>
				<p class="main_text"><img src="/assets/img/gal.png" alt="." height="22" width="auto">Доставка курьером в течении 3 часов</p>
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
			include_once __DIR__ . '/assets/php/blocks/faq_refill.php';
            include_once __DIR__ . '/assets/php/blocks/callback.php';
            include_once __DIR__ . '/assets/php/blocks/feedback.php';
            include_once __DIR__ . '/assets/php/blocks/slider.php';
            include_once __DIR__ . '/assets/php/blocks/gallery.php';
            include_once __DIR__ . '/assets/php/blocks/footer.php';
            include_once __DIR__ . '/assets/php/blocks/footer.php';
						include_once __DIR__ . '/assets/php/blocks/map-pop-up.php';
            if (!isset($_COOKIE["accept_notice"])) {
		 		include_once 'assets/php/blocks/notice.php';
  			}
        ?>

	</div>
</body>
<script type="text/javascript" src="/assets/js/scripts_body.js"></script>
</html>
