$('#photo_file').change(function () {
    $('#photo').val($('#photo_file').val().replace("C:\\fakepath\\", ""));
});

$(document).ready(function () {
    $('#remove_button').prop("onclick", null).off("click");
    $('#remove_button').click(function () {
        window.onbeforeunload = null;
        SUGAR.field.file.deleteAttachment("photo", "", this);
    });
});