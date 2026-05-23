$(document).ready(function () {
    let meetingsEditView = new MeetingsEditView();
    if ($('input[name="duplicateSave"]').val() == 'true') {
        meetingsEditView.changeRepeatToNone();
    }
});

class MeetingsEditView {
    changeRepeatToNone() {
       $('select[name="repeat_type"]').val('').change();
    }
}