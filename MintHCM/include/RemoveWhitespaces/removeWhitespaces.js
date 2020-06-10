removeWhitespaces();

function removeWhitespaces() {
    $('.detail-view-row .detail-view-row-item').each(function () {
        if ($.trim($(this).text()).length == 0) {
            if ($(this).children().length == 0) {
                $(this).remove();
            }
        }
    });
}