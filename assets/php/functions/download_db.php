<?php 
require_once 'connect_db.php';
require_once 'functions_db.php';
require_once 'p.php';

// Создаем новый файл
$temp = tmpfile();

// Получаем все записи из базы
$query = 
"
  SELECT C.cartrige_id, C.series, C.model, C.price_1_pcs, C.price_2_pcs, C.price_5_pcs, C.price_in_office, colors.image_path, colors.color_name 
  FROM cartriges AS C
  JOIN colors ON C.color_id=colors.color_id
  ORDER BY cartrige_id;
";
$stmt = $pdo->query($query);


// Записываем заголовок и базу в файл
$file_header = [
  "ID", 
  "Производитель", 
  "Цвет картриджа", 
  "Серия картриджа", 
  "Модель картриджа", 
  "Цена за 1", 
  "Цена за 2", 
  "Цена за 5", 
  "Цена в офисе", 
  "Серия принтера", 
  "Модель принтера", 
  "Принтер цветной?",
];  
fputcsv($temp, $file_header);

while ($arr = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $id = $arr['cartrige_id'];
  $printer_series_and_models = get_printers_series_and_models($id, $pdo);
  $vendor  = get_printer_vendor($id, $pdo); 
  $colored = get_printer_colored($id, $pdo);

  $printer_models = join(" | ", $printer_series_and_models);
  $printer_series = join(" | ", array_keys($printer_series_and_models));

  $insert_data = [
    $id,
    $vendor,
    $arr['color_name'],
    $arr['series'],
    $arr['model'],
    $arr['price_1_pcs'],
    $arr['price_2_pcs'],
    $arr['price_5_pcs'],
    $arr['price_in_office'],
    $printer_series,
    $printer_models,
    $colored
  ];
  fputcsv($temp, $insert_data);
}

fseek($temp, 0);

// Возвращаем клиенту файл

file_force_download($temp, $file_header);

?>
