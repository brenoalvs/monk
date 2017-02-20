$(document).ready(function() {
  var wH = $(window).height();
  $('.description').css('height', wH);
  $(window).resize(function() {
    var wR = $(window).height();
      $('.description').css('height', wR);
  });
  $(window).scroll(function() {
    $('.feature-content').each(function(s) {
      var text_end = $(this).offset().top + $(this).outerHeight();
      var window_end = $(window).scrollTop() + $(window).height();

      if (window_end >= text_end) {
        $(this).animate({'opacity':'1'}, 800);
        if ($(this).parent().next('div').length != 0) {
          $(this).parent().next('div').animate({'opacity':'1'}, 900);
        } else {
          $(this).parent().prev('div').animate({'opacity':'1'}, 900);
        }
      }
    });
  });
})