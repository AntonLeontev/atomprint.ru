<!-- Форма для загрузки файла с данными на сервер -->
<div class="upload_form_wrap hidden">
  <form method="POST" class="upload_form" action="admin_panel.php" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="500000">
    <input type="file" name='file[]' class="input_file" required="">
    <br>
    <label for="excel">
        <input type="checkbox" name="excel" value="true" id="excel" checked="">
        Файл сохранен экселем
    </label>
    <input type='submit' value='Загрузить' class="btn" id="upload_file_btn" >
  </form>
  <button class="btn" onclick="document.querySelector('.upload_form_wrap').classList.toggle('hidden')">Закрыть</button>
</div>
