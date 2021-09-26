<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once 'assets/php/functions/p.php';      
include_once 'assets/php/classes/FormDataSender.php';
        
// Если переход при отправке данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
    // Подготавливаем данные к отправке
    try {
        $fds = new FormDataSender($_POST);      

        //Если не спам отправляем заявку
        if (!$fds->checkSpam()) {
            $telegram = $fds->sendToTelegram();
            $bitrix   = $fds->sendToBitrix();

            if ($telegram || $bitrix) {                         
                $message = "Принято! Мы скоро позвоним по номеру <br>" . $fds->getPhone() . "!";             
            } else $message = 'Не удалось отправить данные. Пожалуйста, позвоните нам по телефону';  
        } 
    } catch (Exception $e) {
        $message = 'Ошибка. ' . $e->getMessage();
    }       
} else {
    header ("Location: /");
    exit();
}
