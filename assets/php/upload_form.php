<div class="upload_form_wrap hidden">
  <form method="POST" class="upload_form" action="assets/php/functions/upload_file.php" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="500000">
    <input type="file" name='file[]' class="input_file" >
    <br>
    <input type='submit' value='Загрузить' class="btn" id="upload_file_btn" >
  </form>
  <button class="btn" onclick="document.querySelector('.upload_form_wrap').classList.toggle('hidden')">Закрыть</button>
</div>
