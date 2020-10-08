$(document).ready(function () {
   $('#recruitment_name').blur(fillCurrentRecruitment);
});

function recruitmentNameCallBack(data) {
   set_return(data);
   fillCurrentRecruitment();
}

function fillCurrentRecruitment() {
   var recruitment_name_selector = $('#recruitment_name');
   var recruitment_id_selector = $('#recruitment_id');
   var record_id_selector = $('input[name="record"]');
   var recruitment_end_name_selector = $('#recruitment_end_name');
   var recruitment_end_id_selector = $('#recruitment_end_id');
   if (!record_id_selector.val() && recruitment_id_selector.val() && recruitment_name_selector.val()) {
      viewTools.form.setValue(recruitment_end_name_selector, recruitment_name_selector.val());
      viewTools.form.setValue(recruitment_end_id_selector, recruitment_id_selector.val());
   }
}
