// $(document).ready(function() {

  //Анимация карты
  $('.map').click(function() {
    $('#map_background').show('fast')
    $('#map_bar').show('fast')
    $('#map_background').removeClass('hidden')
    $('.map').hide('fast')
  })

  $('#footer_adress').click(function() {
    $('#map_background').show('fast')
    $('#map_bar').show('fast')
    $('#map_background').removeClass('hidden')
    $('.map').hide('fast')
  })

  $('.button').click(function() {
    $('#map_background').hide('fast')
    $('#map_bar').hide('fast')
    $('.map').show('fast')
  })

  $('#map_background').click(function() {
    $('#map_background').hide('fast')
    $('#map_bar').hide('fast')
    $('.map').show('fast')
  })

  //Анимация предупреждения пользователей и установка куки
  $('.notice_btn').click(function() {
    $('.notice_conteiner').animate({
      opacity: "0"
    })
    $.cookie('accept_notice', 'yes', {
      expires: 3600 * 24 * 30,
      path: '/'
    });
  })


  //Подстановка Тайтла страницы в инпуты форм
  title = document.title;
  footerPageTitleInput = document.getElementById("footer_page_title_input"),
  smallFormTitleInput = document.getElementById("small_form_page_title_input"),
  firstscreenFormTitleInput = document.getElementById("firstscreen_form_page_title_input");
  if (footerPageTitleInput != null) {
    footerPageTitleInput.value = title;
  }
  if (smallFormTitleInput != null) {
    smallFormTitleInput.value = title;
  }
  if (firstscreenFormTitleInput != null) {
    firstscreenFormTitleInput.value = title;
  }

  /* Появление ответов на ЧаВо */
  faqConteiner = document.querySelector(".faq_conteiner");
  if (faqConteiner != null) {
    faqConteiner.addEventListener('click', function(event) {
      id = event.target.dataset.toggleId;
      if (!id) return;
      elem = document.getElementById(id);
      elem.classList.toggle("active");
    });
  };
// })


// Измениние цвета активной кнопки в printers_menu
vendors = {
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

// let title = document.title;

for (i in vendors) {

  if ( title.toLowerCase().includes(i) ) {
    selector = '.' + vendors[i];
    el = document.querySelector(selector);
    el.classList.add('active');
  }
}



/* Маска ввода телефона */
$(function() {
  $.mask.definitions['~'] = "[+-]";
  $("#date").mask("99/99/9999", {
    completed: function() {
      alert("completed!");
    }
  });
  $(".phone_inp").mask("+7(999)999-99-99");
  $("#phoneExt").mask("(999) 999-9999? x99999");
  $("#iphone").mask("+33 999 999 999");
  $("#tin").mask("99-9999999");
  $("#ssn").mask("999-99-9999");
  $("#product").mask("a*-999-a999", {
    placeholder: " "
  });
  $("#eyescript").mask("~9.99 ~9.99 999");
  $("#po").mask("PO: aaa-999-***");
  $("#pct").mask("99%");

  $("input").blur(function() {
    $("#info").html("Unmasked value: " + $(this).mask());
  }).dblclick(function() {
    //  $(this).unmask();
  });
});

/*  Конец маски ввода телефона */

// Плагин venobox
$('.venobox').venobox({
    arrowsColor: "#FF9E21",
    border     : '0',                             // default: '0'
    bgcolor    : '#fff',                          // default: '#fff'
    titleattr  : 'data-title',                       // default: 'title'
    numeratio  : true,                               // default: false
    infinigall : true,                               // default: false
    share      : [] // default: []
});
