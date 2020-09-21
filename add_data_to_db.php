<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add new cartrige</title>
</head>
<body>
  <form action="assets/php/functions/add_data.php" method="POST">
    <input type="number" name="id" placeholder="id">
    <select name="color" value='color'>
      <option value="black" name='black'>Black</option>
      <option value="cyan">Cyan</option>
      <option value="yellow">Yellow</option>
      <option value="magenta">Magenta</option>
    </select>
    <input type="text" name="series" placeholder="series">
    <input type="text" name="model" placeholder="model">
    <input type="number" name="price_1_pcs" placeholder="price_1_pcs">
    <input type="number" name="price_2_pcs" placeholder="price_2_pcs">
    <input type="number" name="price_5_pcs" placeholder="price_5_pcs">
    <input type="number" name="price_in_office" placeholder="price_in_office">
    <input type="submit" value="Add">
  </form>
</body>
</html>