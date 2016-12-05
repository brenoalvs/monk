$(document).ready(function() {
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