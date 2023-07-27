<?php

ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(1);


// формируем URL в переменной $queryUrl
$queryUrl = 'https://b24-kc1wpe.bitrix24.ru/rest/1/ly5bjer0c86qo39q/user.get.json';

// формируем параметры для создания лида в переменной $queryData
$queryData = http_build_query(array(
	'fields' => [
	'TITLE' => "Заявка с сайта",
	'NAME' => $name,
	'COMMENTS' => "Заявка!\n\r{$txt}",
	'PHONE' => [
		"n0" => [
			"VALUE" => $phone,
			"VALUE_TYPE" => "WORK",
		],
	],
	"SOURCE_ID" => "Сайт",
	'WEB' => $pageTitle,


	],
	'params' => array("REGISTER_SONET_EVENT" => "Y")
));

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_POST => false,
	CURLOPT_HEADER => 0,
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => $queryUrl,
	// CURLOPT_POSTFIELDS => $queryData,
));
$result = curl_exec($curl);
curl_close($curl);
// $result = json_decode($result);
// if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";

echo($result);
