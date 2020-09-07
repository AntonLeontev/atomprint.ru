
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Тестовая страница oki | Атомпринт</title>
	<meta name="description" content="Атомпринт - сервисная компания по ремонту принтеров и любой оргтехники. Занимаемся заправкой картриджей, обслуживанием и чисткой МФУ. Наша задача - сделать так, чтобы у вас в офисе все хорошо и правильно печатало">
	<meta name="keywords" content="заправка картриджей,ремонт принтеров,ремонт мфу">
</head>
<style>
	.new_class {color: red; text-decoration: none;}
</style>
<body>	
	<nav class="printers_menu">
  <div class="printers_menu_title">Стоимость заправки картриджей:</div>
  <ul class="printers_menu_list">
    <li class="printers_menu_item item_hp"><a href="/prices/price_hp.php">HP</a></li>
    <li class="printers_menu_item item_canon"><a href="/prices/price_canon.php">Canon</a></li>
    <li class="printers_menu_item item_samsung"><a href="/prices/price_samsung.php">Samsung</a></li>
    <li class="printers_menu_item item_xerox"><a href="/prices/price_xerox.php">Xerox</a></li>
    <li class="printers_menu_item item_kiocera"><a href="/prices/price_kiocera.php">Kiocera</a></li>
    <li class="printers_menu_item item_brother"><a href="/prices/price_brother.php">Brother</a></li>
    <li class="printers_menu_item item_panasonic"><a href="/prices/price_panasonic.php">Panasonic</a></li>
    <li class="printers_menu_item item_oki"><a href="/prices/price_oki.php">OKI</a></li>
    <li class="printers_menu_item item_konika"><a href="/prices/price_konika.php">Konika</a></li>
  </ul>
</nav>


	
</body>
<script>
	let vendors = {
  "hp"        : 'item_hp',
  "canon"     : 'item_canon',
  "samsung"   : 'item_samsung',
  "xerox"     : 'item_xerox',
  "kiocera"   : 'item_kiocera',
  "brother"   : 'item_brother',
  "panasonic" : 'item_panasonic',
  "oki"       : 'item_oki',
  "konika"    : 'item_konika'
};

let title = document.title;

for (let i in vendors) {

  if ( title.toLowerCase().includes(i) ) {
    let selector = '.' + vendors[i];
  }
}
let sams = document.querySelector('.item_samsung');
alert(sams.classList);
sams.classList.add('new_class');
alert(sams.classList);
console.log('final');

</script>