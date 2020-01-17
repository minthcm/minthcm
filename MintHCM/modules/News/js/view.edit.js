$(document).ready(function () {
    $('select#news_type').change(setPublicationDate);
});

function setPublicationDate() {
    var publicationDate = $('input#publication_date');
    if ($(event.target).val() === 'announcement' && publicationDate.val() === "") {
        publicationDate.val(moment().format(viewTools.date.getDateFormat()));
    }
}
