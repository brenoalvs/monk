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

      if (window_end > text_end) {
        $(this).animate({'opacity':'1'}, 700);
      }
    });

    $('.feature-image').each(function(s) {
      var image_end = $(this).offset().top + $(this).outerHeight();
      var window_end = $(window).scrollTop() + $(window).height();

      if (window_end > image_end) {
        $(this).animate({'opacity':'1'}, 700);
      }
    });
  });
})