<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Спасибо за заявку!</title>
	<?php
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(0);
		include_once 'assets/php/head.php';
	 ?>
</head>
<body>
	<?php
	include_once __DIR__ . '/assets/php/header.php';
	include_once __DIR__ . '/assets/php/map-pop-up.php';
	 ?>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST['phone'])){
	  if (isset($_POST['name'])) {
	    if (!empty($_POST['name'])){
	  $name = strip_tags(mb_strtoupper($_POST['name']));
	  $nameFieldset = "Имя: ";
	  	}
		}

	if (isset($_POST['phone'])) {
	  if (!empty($_POST['phone'])){
	  $phone = strip_tags($_POST['phone']);
	  $phoneFieldset = "Телефон: ";
	  }
	}
	if (isset($_POST['form-name'])) {
	  if (!empty($_POST['form-name'])){
	  $formName = strip_tags($_POST['form-name']);
	  $formNameFieldset = "Форма: ";
	  }
	}
	if (isset($_POST['page-title'])) {
	  if (!empty($_POST['page-title'])){
	  $pageTitle = strip_tags($_POST['page-title']);
	  $pageTitleFieldset = "Страница: ";
	  }
	}
	$token = "961313657:AAGAMoIvveEHv3GiEC_Sed4uXByUPvLZXiA";
	$chat_id = "-322308753";
	$arr = array(
	  $nameFieldset => $name,
	  $phoneFieldset => $phone,
		$pageTitleFieldset => $pageTitle,
		$formNameFieldset => $formName,
	);
	foreach($arr as $key => $value) {
	  $txt .= "<b>".$key."</b> ".$value."%0A";
	};
	$sendMailAner = mail('aner-anton@ya.ru', 'НОВАЯ ЗАЯВКА!', "\r\n\r\nИмя: $name; \r\nТелефон: $phone \r\n\r\nЗвони скорее!!!\r\n\r\nСтраница: $pageTitle\r\nФорма: $formName");
	$sendMailAtom = mail('atomprint@yandex.ru', 'НОВАЯ, ЗАЯВКА!', "\r\n\r\nИмя: $name; \r\nТелефон: $phone \r\n\r\nЗвони скорее!!!\r\n\r\nСтраница: $pageTitle\r\nФорма: $formName");
	$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");
	$message = 'Принято!';
	if ($sendToTelegram) {

	  $message = "Принято! Мы скоро позвоним по номеру <br>$phone!";
	    //return true;
	} else {
	  $message = 'Ошибка. Сообщение не отправлено! Попробуйте позвонить нам';
	}
} else {
	  $message = 'Ошибка. Введите номер телефона еще раз';
	}
} else {
	header ("Location: /");
}

?>


<div class="thanks_page">
	<h1 class="h1"><?php echo $message ?></h1>
	<img src="/assets/img/call-print.jpg" alt="Заявка принята">
	<button class="tomain_btn" onclick="document.location='/index.php'">Перейти на главную</button>
</div>


</body>
</html>
