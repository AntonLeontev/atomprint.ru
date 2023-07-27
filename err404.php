<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Страница не найдена</title>
	<?php
		include_once 'assets/php/blocks/head.php'
	 ?>
</head>
<body>
<?php
	include_once 'assets/php/blocks/header.php'
?>
<div class="background404">
	<div class="container404">
		<div class="text404">
			<h1 class="title404">404</h1>
			<p>Страница не найдена</p>
			<button class="btn" onclick="document.location='/index.php'">Перейти на главную</button>
		</div>
		<div class="img404">
			<img src="/assets/img/404.png" alt="Страница не найдена">
		</div>
	</div>
</div>
</body>
</html>
