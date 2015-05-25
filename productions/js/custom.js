/**
 * Created by Michael on 3/12/2015.
 */
function scroll(speed) {
    $('html, body').animate({scrollTop: $(document).height() - $(window).height()}, speed, function () {
        $(this).animate({scrollTop: 0}, speed);
    });
}

function load() {
    speed = 500;
    scroll(speed);
    setInterval(function () {
        scroll(speed)
    }, speed * 2);
}