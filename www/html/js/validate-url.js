// 表示する際にURLを有効化（下記二つ）
$(function () {
    $('.js-autolink').each(function () {
        $(this).html($(this).html().replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, "<a href='$1' class='m-0'>$1</a>"));
    });
});

$(function () {
    $('.js-menu__item__link').each(function () {
        $(this).on('click', function () {
            $(this).toggleClass('on');
            $("+.submenu", this).slideToggle()
            return false;
        });
    });
});
