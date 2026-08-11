
$(document).ready(function () {
    $('.menu-mob').click(function () {
        $('.mobile-menu ul').slideToggle(400);
        $(this).toggleClass('open');
    });
});