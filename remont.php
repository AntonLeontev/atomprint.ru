<?php
include_once 'assets/php/blocks/cookies.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Ремонт и обслуживание принтеров в Екатеринбурге | Атомпринт</title>
	<meta name="description" content="Ремонт принтеров и МФУ. Чистка и обслуживание печатной техники в Екатеринбурге">
	<meta name="keywords" content="заправка картриджей,ремонт принтеров,ремонт мфу">
	<?php
        include_once 'assets/php/blocks/head.php'
     ?>

</head>
<body>
	<?php
    include_once 'assets/php/blocks/header.php';
		include_once 'assets/php/blocks/printers_menu.php';			
     ?>
	<div class="content">
		<div class="first_screen">
			<h1 class="h1">Ремонт и обслуживание принтеров</h1>
			<h2 class="h2">Профессиональный сервисный центр в Екатеринбурге</h2>
			<form method="post" action="/thanks_page.php" id="callback_first_screen" onsubmit="ym('53149000','reachGoal', '1form');">
				<div class="Ost">Просто оставьте свой номер</div>
				<div class="Mast">Мастер перезвонит в течении 15 минут и поможет решить проблему с принтером</div>
				<input class="input_text" type="text" name="name" placeholder=" Имя" maxlength="30" tabindex="1" />
				<input class="phone_inp" type="tel" name="phone" required placeholder=" Телефон*" tabindex="2" />
				<input type="hidden" name="form-name" value="Первый экран"/>
				<input type="hidden" id="firstscreen_form_page_title_input" name="page-title" value="Ремонт принтеров"/>
				<label class="consent"><input type="checkbox" required />Даю согласие на обработку персональных данных</label>
				<input class="input_submit" type="submit" name="btn" class="btn" value="Оставить контакты" tabindex="3" />
			</form>
		</div>
	<?php
        include_once __DIR__ . '/assets/php/blocks/profeets.php';
        include_once __DIR__ . '/assets/php/blocks/feedback.php';
        include_once __DIR__ . '/assets/php/blocks/slider.php';
        include_once __DIR__ . '/assets/php/blocks/gallery.php';
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
