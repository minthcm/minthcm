$(document).ready(function () {
    $('<div id="alert_create_dialog_confirmation"></div>').appendTo("body").dialog({
        modal: true,
        title: "",
        zIndex: 10000,
        autoOpen: false,
        width: "auto",
        height: "auto",
        resizable: false,
    });
    $("#create-users-group").on("click", function () {
        showCreateGroupModal();
    });
    $(".update-users-group").on("click", function () {
        updateGroupConfirmation($(this).attr('value'));
    });
    $(".delete-users-group").on("click", function () {
        deleteGroupConfirmation($(this).attr('value'));
    });
    $(".users-group-select").on("click", function (e) {
        if(e.target == this) {
            showLoadingScreen('Calendar', viewTools.language.get('app_strings', 'LBL_LOADING'));
            selectGroup($(this).attr('value'));
        }
    });
    $("#sharedCalUsersSelectBtn").on("click", function () {
        unselectGroup();
    });

});

function showCreateGroupModal() {
    $('.modal-group-creation').modal('toggle');
    $('#group-creation-empty-group-name').hide();
    $('#group-creation-group-name').val('');
    $('#group-creation-create-button').on('click', function () {
        if($('#group-creation-group-name').val().trim() == '') {
            $('#group-creation-empty-group-name').show();
        } else {
            $('#shared_cal').submit();
            saveGroup($('#group-creation-group-name').val());
            $('.modal-group-creation').modal('hide');
        }
    });
}

function saveGroup(group_name) {
    showLoadingScreen(viewTools.language.get("app_strings", "LBL_SAVING"), viewTools.language.get("app_strings", "LBL_LOADING"));
    var user_ids = getUserIds();
    viewTools.api.callController({
        module: "Calendar",
        action: "createUpdateGroup",
        dataType: "json",
        async: false,
        dataPOST: {
            group_name: group_name,
            user_ids: user_ids,
        },
        callback: function (call_constroller_data) {
            if (
                call_constroller_data == false ||
                call_constroller_data == null
            ) {
                console.error(call_constroller_data);
                closeLoadingScreen();
                viewTools.GUI.statusBox.showStatus(
                    SUGAR.language.get("app_strings", "LBL_ERROR"),
                    "error",
                    3000
                );
            } else {
                location.reload();
            }
        },
    });
}

function deleteGroup(group_name) {
    showLoadingScreen(viewTools.language.get("app_strings", "LBL_SAVING"), viewTools.language.get("app_strings", "LBL_LOADING"));
    viewTools.api.callController({
        module: "Calendar",
        action: "deleteGroup",
        dataType: "json",
        async: false,
        dataPOST: {
            group_name: group_name,
        },
        callback: function (call_constroller_data) {
            if (
                call_constroller_data == false ||
                call_constroller_data == null
            ) {
                console.error(call_constroller_data);
                closeLoadingScreen();
                viewTools.GUI.statusBox.showStatus(
                    SUGAR.language.get("app_strings", "LBL_ERROR"),
                    "error",
                    3000
                );
            } else {
                location.reload();
            }
        },
    });
}

function selectGroup(group_name) {
    showLoadingScreen(viewTools.language.get("app_strings", "LBL_SELECT_BUTTON_LABEL"), viewTools.language.get("app_strings", "LBL_LOADING"));
    viewTools.api.callController({
        module: "Calendar",
        action: "selectGroup",
        dataType: "json",
        async: false,
        dataPOST: {
            group_name: group_name,
        },
        callback: function (call_constroller_data) {
            if (
                call_constroller_data == false ||
                call_constroller_data == null
            ) {
                console.error(call_constroller_data);
                closeLoadingScreen();
                viewTools.GUI.statusBox.showStatus(
                    SUGAR.language.get("app_strings", "LBL_ERROR"),
                    "error",
                    3000
                );
            } else {
                location.reload();
            }
        },
    });
}

function getUserIds() {
    var user_ids = [];
    $.each($("#shared_ids option:selected"), function (key, value) {
        user_ids.push(value.value);
    });
    return user_ids;
}

function updateGroupConfirmation(group_name) {
    var dialog = $("#alert_create_dialog_confirmation");
    var dialog_buttons = {};
    dialog_buttons[SUGAR.language.get("app_strings", "LBL_DIALOG_YES")] =
        function () {
            $(this).dialog("close");
            saveGroup(group_name);
        };
    dialog_buttons[SUGAR.language.get("app_strings", "LBL_DIALOG_NO")] =
        function () {
            $(this).dialog("close");
        };
    dialog
        .html(
            "<p>" +
                SUGAR.language.get(
                    "Calendar",
                    "LBL_UPDATE_USERS_GROUP_CONFIRM"
                ) + ' <b>' + group_name + '?</b>' +
                "</p>"
        )
        .dialog({ buttons: dialog_buttons })
        .dialog("open")
        .show();
}

function deleteGroupConfirmation(group_name) {
    var dialog = $("#alert_create_dialog_confirmation");
    var dialog_buttons = {};
    dialog_buttons[SUGAR.language.get("app_strings", "LBL_DIALOG_YES")] =
        function () {
            $(this).dialog("close");
            deleteGroup(group_name);
        };
    dialog_buttons[SUGAR.language.get("app_strings", "LBL_DIALOG_NO")] =
        function () {
            $(this).dialog("close");
        };
    dialog
        .html(
            "<p>" +
                SUGAR.language.get(
                    "Calendar",
                    "LBL_DELETE_USERS_GROUP_CONFIRM"
                ) + ' <b>' + group_name + '?</b>' +
                "</p>"
        )
        .dialog({ buttons: dialog_buttons })
        .dialog("open")
        .show();
}

function unselectGroup() {
    var user_ids = getUserIds();
    viewTools.api.callController({
        module: "Calendar",
        action: "unselectGroup",
        dataType: "json",
        async: false,
        dataPOST: {
            user_ids: user_ids,
        },
        callback: function (call_constroller_data) {
            if (
                call_constroller_data == false ||
                call_constroller_data == null
            ) {
                console.error(call_constroller_data);
                viewTools.GUI.statusBox.showStatus(
                    SUGAR.language.get("app_strings", "LBL_ERROR"),
                    "error",
                    3000
                );
            } else {
                location.reload();
            }
        },
    });
}


