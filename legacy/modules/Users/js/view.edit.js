
function displayConfirmationWindow(response) {
    let units_links = '';
    let dialog_div = '';
    for (const [key, value] of Object.entries(response)) {
        units_links += '<a href="' + location["href"] + '?module=SecurityGroups&action=DetailView&record=' + key + '" target="_blank">' + value + '</a></br>';
    }
    var question = viewTools.language.get('Users', 'LBL_USER_DEACTIVE_SUPERVISOR');
    dialog_div = $('<div>').css('display', 'none').html(question.replace('<URL>',units_links));

    dialog_div.dialog({
        resizable: false,
        height: 300,
        width: 500,
        modal: true,
        buttons: [
            {
                text: viewTools.language.get('Users', 'LBL_USERS_CONFIRMATION_BUTTON_CONFIRM'),
                click: function () {
                    $(this).dialog("close");
                    window["users_editview_units_popup"] = true;
                    viewTools.form.prepareViewToolsValidation.call($('#SAVE_HEADER').get(0));
                },
            },
            {
                text: viewTools.language.get('Users', 'LBL_USERS_CONFIRMATION_BUTTON_CANCEL'),
                click: function () {
                    $(this).dialog("close");
                },
            }
        ]
  });
}

viewTools.form.afterSave(function(){
    let status = $('select[name="status"] option:selected').val();
    let employee_status = $('select[name="employee_status"] option:selected').val();
    let employee_id = $('#record').val();
    let result = false;
    if(
        (
            $("#confirm_pwd").val().length <= 0 
            && $("#new_password").val().length <= 0 
            && employee_id === ''
        )
        || $("#new_password").val().length !== $("#confirm_pwd").val().length
        || $("#new_password").val() !== $("#confirm_pwd").val()
    ){
        setTimeout(function(){
            viewTools.GUI.statusBox.hideStatus();
            if($("#new_password").val().length <= 0){
                viewTools.GUI.statusBox.showStatus(ERR_ENTER_NEW_PASSWORD, 'error', 4000);
            }
            if($("#new_password").val().length > 0 && $("#confirm_pwd").val().length <= 0 ){
                viewTools.GUI.statusBox.showStatus(ERR_ENTER_CONFIRMATION_PASSWORD, 'error', 4000);
            }
        }, 50);
        $("#tab2")[0].click();
        $("#dlg_mask").remove();
        return false;    
    }
    if (!!window["users_editview_units_popup"] === false && (employee_status != 'Active' || status != 'Active') && employee_id != '') {
        viewTools.api.callCustomApi({
            module: 'Employees',
            action: 'checkIfEmployeeIsSupervisor',
            async: false,
            dataPOST: {employee_id: employee_id, employee_status: employee_status},
            callback: function (response) {
                if (response != false) {
                    displayConfirmationWindow(response);
                }
                else {
                    result = true;
                }
            }
        });
    } else {
        result = true;
    }
    return result;
});