$(document).ready(function () {
    const style = document.createElement('link');
    style.rel = 'stylesheet';
    style.href = 'modules/Rooms/css/jquery.magnify.min.css';
    document.querySelector('head').appendChild(style);

    $('[data-magnify=gallery]').magnify();
});
