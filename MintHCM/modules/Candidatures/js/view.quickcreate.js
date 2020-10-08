$(document).ready(function () {
    fillCurrentRecruitment();
    $('#recruitment_name').blur(fillCurrentRecruitment);
});

function fillCurrentRecruitment() {
    var recruitment_name_selector = $('#recruitment_name');
    var recruitment_id_selector = $('#recruitment_id');
    var recruitment_end_name_selector = $('#recruitment_end_name');
    var recruitment_end_id_selector = $('#recruitment_end_id');
    if (recruitment_id_selector.val() && recruitment_name_selector.val()) {
        viewTools.form.setValue(recruitment_end_name_selector, recruitment_name_selector.val());
        viewTools.form.setValue(recruitment_end_id_selector, recruitment_id_selector.val());
    }
}
