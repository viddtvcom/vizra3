function showHide(element) {
    element = document.getElementById(element);
    if (element.style.display == '') {
        element.style.display = 'none';
        return 0;
    } else {
        element.style.display = '';
        return 1;
    }
}

function setFrameSrc(frame, url) {
    frame = document.getElementById(frame);
    frame.src = url;
}


function raise_msg(msg, type) {
    $('#msg_container').fadeOut().children(':first').children(':first').removeClass().addClass(type).children(':last').html(msg);
    $('#msg_container').fadeIn().fadeOut(9000);
}
