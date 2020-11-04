//Именение цвета сообщения, при отсутствии ошибок
let params = (new URL(document.location)).searchParams; 

let errWrap = document.querySelector(".error_wrap");
  if (errWrap != null && params.get('err') == '0') {
    document.querySelector('.error_wrap').style.background = '#1d8c07';
  }
