<?php 

if (!isset($_COOKIE["return"])) {
 	$token = "returned_user";	
	setcookie("return", $token, time()+60*60*24*30);
}
 		
?>
