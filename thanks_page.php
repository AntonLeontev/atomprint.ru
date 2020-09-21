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

		function mb_ucfirst($string) {
      $first = mb_substr($string, 0, 1);
      $else = mb_substr($string, 1);
      return mb_strtoupper($first).mb_strtolower($else);
    }
	 ?>
</head>
<body>
	<?php
	include_once __DIR__ . '/assets/php/header.php';
	include_once __DIR__ . '/assets/php/map-pop-up.php';
	 ?>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Подготавливаем данные к отправке

		if (!empty($_POST['phone'])){ 
		  if (isset($_POST['name'])) {
		    if (!empty($_POST['name'])){
		  	$name = strip_tags(mb_ucfirst($_POST['name']));
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

			// для отправки в телеграм
			$token = "961313657:AAGAMoIvveEHv3GiEC_Sed4uXByUPvLZXiA";
			$chat_id = "-322308753";
			$arr = array(
			  $nameFieldset      => $name,
			  $phoneFieldset     => $phone,
				$pageTitleFieldset => $pageTitle,
				$formNameFieldset  => $formName,
			);
			foreach($arr as $key => $value) {
			  $txt .= "<b>".$key."</b> ".$value."\n";
			};

			//для отправки в битрикс
			// формируем URL в переменной $queryUrl
			$queryUrl = 'https://b24-kc1wpe.bitrix24.ru/rest/1/ly5bjer0c86qo39q/crm.lead.add.json';

			// формируем параметры для создания лида в переменной $queryData
			$queryData = http_build_query(array(
			  'fields' => array(
			    'TITLE'    => "Заявка с сайта",
			    'NAME'     => $name,
			    'COMMENTS' => "Заявка!\n".$txt,
			  	'PHONE'    => Array(
				    "n0"  => Array(
				        "VALUE" => $phone,
				        "VALUE_TYPE" => "WORK",
				    ),
					),
					"SOURCE_ID" => "Сайт",
					'WEB' => $pageTitle,


			  ),
			  'params' => array("REGISTER_SONET_EVENT" => "Y")
			));

			if ($_POST['page-title'] != 'Нет данных') { //проверка на спам

			// отправляем письма
				// $sendMailAner = mail('aner-anton@ya.ru', 'НОВАЯ ЗАЯВКА!', "\r\n\r\nИмя: $name; \r\nТелефон: $phone \r\n\r\nЗвони скорее!!!\r\n\r\nСтраница: $pageTitle\r\nФорма: $formName");
				// $sendMailAtom = mail('atomprint@yandex.ru', 'НОВАЯ, ЗАЯВКА!', "\r\n\r\nИмя: $name; \r\nТелефон: $phone \r\n\r\nЗвони скорее!!!\r\n\r\nСтраница: $pageTitle\r\nФорма: $formName");

				// отправляем в телеграм
				$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");

				// отправляем в битрикс 
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_SSL_VERIFYPEER => 0,
				  CURLOPT_POST => 1,
				  CURLOPT_HEADER => 0,
				  CURLOPT_RETURNTRANSFER => 1,
				  CURLOPT_URL => $queryUrl,
				  CURLOPT_POSTFIELDS => $queryData,
				));
				$result = curl_exec($curl);
				curl_close($curl);
				$result = json_decode($result, 1);
				if (array_key_exists('error', $result)) {
					mail('aner-anton@ya.ru', 'Ошибка при сохранении лида в битрикс', "Ошибка при сохранении лида: ".$result['error_description']);
				} 

				if ($sendToTelegram || $sendMailAtom) {
				  $message = "Принято! Мы скоро позвоним по номеру <br>$phone!";
				} else {
			  $message = 'Ошибка. Сообщение не отправлено! Попробуйте позвонить нам';
				}
			} else { 
			// если спам
				$sendMailAner = mail('aner-anton@ya.ru', 'СПАМ-ФИЛЬТР АТОМПРИНТ', "\r\n\r\nИмя: $name; \r\nТелефон: $phone \r\n\r\nЗвони скорее!!!\r\n\r\nСтраница: $pageTitle\r\nФорма: $formName");
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
