$(function () {
  var pagetop = $('#page_top');
  // ボタン非表示
  pagetop.hide();
  // 100px スクロールしたらボタン表示
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      pagetop.fadeIn();
    } else {
      pagetop.fadeOut();
    }
  });
  pagetop.click(function () {
    $('body, html').animate({ scrollTop: 0 }, 500);
    return false;
  });
});

$(function () {
  $(window).on("scroll", function () {
    $('.thum').each(function (index, el) {
      if ($(window).scrollTop() > ($(el).offset().top - $(window).height() / 1.1)) {
        $(el).addClass('is-over');
      }
    });
  });
});
