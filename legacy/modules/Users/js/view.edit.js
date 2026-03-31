function getPasswordValues() {
    return {
        newPassword: $("#new_password").val() || '',
        confirmPassword: $("#confirm_pwd").val() || ''
    };
}

function showPasswordError(message) {
    setTimeout(function () {
        viewTools.GUI.statusBox.hideStatus();
        viewTools.GUI.statusBox.showStatus(message, 'error', 4000);
    }, 50);

    $("#tab2")[0].click();
    $("#dlg_mask").remove();
}

function validatePasswordFields(employeeId) {
    const { newPassword, confirmPassword } = getPasswordValues();
    
    if(employeeId && !newPassword && !confirmPassword){
        return true;
    }

    if (!employeeId && !newPassword && !confirmPassword) {
        showPasswordError(ERR_ENTER_NEW_PASSWORD);
        return false;
    }

    if (!newPassword) {
        showPasswordError(ERR_ENTER_NEW_PASSWORD);
        return false;
    }

    if (!confirmPassword) {
        showPasswordError(ERR_ENTER_CONFIRMATION_PASSWORD);
        return false;
    }

    if (newPassword.length !== confirmPassword.length || newPassword !== confirmPassword) {
        $("#tab2")[0].click();
        $("#dlg_mask").remove();
        return false;
    }

    return true;
}

function validatePasswordRequirements() {
    let errorMessage = false;

    viewTools.api.callCustomApi({
        module: 'Users',
        action: 'passwordValidationCheck',
        async: false,
        dataPOST: { password: $("#new_password").val() },
        callback: function (response) {
            if (!response || !response.message) {
                errorMessage = false;
                return;
            }
            errorMessage = response.message;
        }
    });

    if (errorMessage) {
        showPasswordError(errorMessage);
        return false;
    }

    return true;
}

function displayConfirmationWindow(response) {

    let unitsLinks = '';

    for (const [key, value] of Object.entries(response)) {
        unitsLinks +=
            `<a href="${location.href}?module=SecurityGroups&action=DetailView&record=${key}" target="_blank">${value}</a><br>`;
    }

    const question = viewTools.language.get('Users', 'LBL_USER_DEACTIVE_SUPERVISOR');
    const dialogDiv = $('<div>')
        .css('display', 'none')
        .html(question.replace('<URL>', unitsLinks));

    dialogDiv.dialog({
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
                }
            },
            {
                text: viewTools.language.get('Users', 'LBL_USERS_CONFIRMATION_BUTTON_CANCEL'),
                click: function () {
                    $(this).dialog("close");
                }
            }
        ]
    });
}

viewTools.form.afterSave(function () {

    const status = $('select[name="status"]').val();
    const employeeStatus = $('select[name="employee_status"]').val();
    const employeeId = $('#record').val();
    const changePassword = $('#change_pwd').val() === '1';
    const systemGeneratedPassword = $('#systemGeneratedPasswordSetting').val() === '1';

    let result = false;
    if (changePassword && !systemGeneratedPassword && $("#required_password").val() !== '0') {
        if (!validatePasswordFields(employeeId)) {
            return false;
        }
        if (!validatePasswordRequirements()) {
            return false;
        }
    }

    if (
        !window["users_editview_units_popup"] &&
        (!['Active', 'during_termination'].includes(employeeStatus) || status !== 'Active') &&
        employeeId
    ) {

        viewTools.api.callCustomApi({
            module: 'Employees',
            action: 'checkIfEmployeeIsSupervisor',
            async: false,
            dataPOST: {
                employee_id: employeeId,
                employee_status: employeeStatus
            },
            callback: function (response) {
                if (response) {
                    displayConfirmationWindow(response);
                } else {
                    result = true;
                }
            }
        });

    } else {
        result = true;
    }

    $("#dlg_mask").remove();
    return result;
});
