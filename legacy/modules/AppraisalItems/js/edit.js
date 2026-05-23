$(document).ready(function () {
    setSubjectList();
    $('#appraisal_name').blur(setSubjectList);
});


function setSubjectList() {
    var appraisal_id = $('#appraisal_id').val();
    if (appraisal_id != '') {
        viewTools.api.callCustomApi({
            module: 'Appraisals',
            action: 'getAppraisalType',
            dataPOST: {
                appraisal_id: appraisal_id,
            },
            callback: function (data) {
                if (data == 'recruiting') {
                    $('#parent_type option[value=Goals]').css('display', 'none');
                    $('#parent_type option[value=Responsibilities]').css('display', 'none');
                    $("#parent_type").val("Competencies").change();
                } else {
                    $('#parent_type option').removeAttr('style');
                }
            }

        });
    } else {
        $('#parent_type option').removeAttr('style');
    }

}