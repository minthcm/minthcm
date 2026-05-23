$(document).ready(function () {
    $('<div id="alert_dialog"></div>').appendTo('body').dialog({
        modal: true,
        title: "",
        zIndex: 10000,
        autoOpen: false,
        width: 'auto',
        height: 'auto',
        resizable: false
    });
    $("#CloseButton").click(function (event) {
        const button = new CloseButton();
        button.click(event.target);
    });
});

class CloseButton extends Button {

    click(element) {
        super.click(element);
        this.dialogHandler = $('#alert_dialog');
        this.beforeClosePlan();
    }

    beforeClosePlan() {
        var workschedule_id = this.getRecordID();
        if (workschedule_id == '' || workschedule_id == null || workschedule_id == undefined) {
            var dialog_buttons = {};
            dialog_buttons[SUGAR.language.get('app_strings', 'LBL_DIALOG_OK')] = function () {
                this.dialogHandler.dialog("close");
            }.bind(this);
            this.dialogHandler.html('<p>' + SUGAR.language.get('app_strings', 'LBL_CHOOSE_PLAN') + '</p>').dialog({ buttons: dialog_buttons }).dialog('open').show();
        } else {
            var dialog_buttons = {};
            dialog_buttons[SUGAR.language.get('app_strings', 'LBL_DIALOG_YES')] = function () {
                this.dialogHandler.dialog("close");
                const record_id = this.getRecordID();
                var planType = $('#type').val() || (this.getTimePanel().taskman._currentPlans.filter(function (i) {
                    return i.id == record_id
                }))[0].type;
                // MintHCM #111673 START
                //var dontCheck = [ 'holiday', 'sick', 'occasional_leave', 'overtime', 'excused_absence', 'leave_at_request'].indexOf( planType ) >= 0;
                var dontCheck = ['holiday', 'sick', 'occasional_leave', 'overtime', 'excused_absence', 'leave_at_request', 'child_care'].indexOf(planType) >= 0;
                // MintHCM #111673 END
                if (dontCheck || this.checkIfCanBeClosed()) {
                    this.closePlan();
                }
            }.bind(this);
            dialog_buttons[SUGAR.language.get('app_strings', 'LBL_DIALOG_NO')] = function () {
                this.dialogHandler.dialog("close");
            }.bind(this);
            this.dialogHandler.html('<p>' + SUGAR.language.get('app_strings', 'LBL_CLOSE_PLAN_CONFIRM') + '</p>').dialog({ buttons: dialog_buttons }).dialog('open').show();
        }
    }

    getTimePanel() {
        return TimePanel.instances[TimePanel.instances.length - 1];
    }

    checkScheduleName(workschedule_id) {
        var result = "null";
        viewTools.api.callController({
            module: "WorkSchedules",
            action: "checkScheduleName",
            dataType: 'text',
            async: false,
            dataPOST: {
                id: workschedule_id
            },
            callback: function (call_constroller_data) {
                result = call_constroller_data;
            }
        });
        return result;
    }

    checkIfCanBeClosed() {
        var result = true;
        var workschedule_id = this.getRecordID() || this.getTimePanel().taskman.$planSelect.val();
        var schedule_name = this.checkScheduleName(workschedule_id);
        const callbackFunction = function (call_constroller_data) {
            if (call_constroller_data != "1") {
                var dialog_buttons = {};
                dialog_buttons[SUGAR.language.get('app_strings', 'LBL_DIALOG_OK')] = function () {
                    this.dialogHandler.dialog("close");
                }.bind(this);
                if (call_constroller_data == "2") {
                    this.dialogHandler.html('<p>' + viewTools.language.get('WorkSchedules', 'ERR_SPENT_TIMES_DO_NOT_OVERLAP_WITH_WORK_SCHEDULE').replace('{name}', schedule_name) + '</p>').dialog({ buttons: dialog_buttons }).dialog('open').show();
                }
                if (call_constroller_data == "3") {
                    this.dialogHandler.html('<p>' + viewTools.language.get('WorkSchedules', 'ERR_WORKPLACE_IS_REQUIRED').replace('{name}', schedule_name) + '</p>').dialog({ buttons: dialog_buttons }).dialog('open').show();
                }
                else if (call_constroller_data == "4") {
                    this.dialogHandler.html('<p>' + viewTools.language.get('WorkSchedules', 'ERR_WORKPLACE_IS_NOT_ACTIVE').replace('{name}', schedule_name) + '</p>').dialog({ buttons: dialog_buttons }).dialog('open').show();
                }

                result = false;
            }
        }.bind(this)
        viewTools.api.callController({
            module: "WorkSchedules",
            action: "checkIfCanBeClosed",
            dataType: 'json',
            async: false,
            dataPOST: {
                id: workschedule_id
            },
            callback: callbackFunction
        });
        return result;
    }

    closePlan() {
        viewTools.GUI.statusBox.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'), 'info');
        var workschedule_id = this.getRecordID();
        const callbackFunction = function (call_constroller_data) {
            if (call_constroller_data == false || call_constroller_data == null) {
                console.error(call_constroller_data);
                viewTools.GUI.statusBox.hideStatus()
            } else {
                if (this.isButtonInDashlet()) {
                    SUGAR.mySugar.retrieveDashlet(this.getDashletID(), '', function () {
                        viewTools.GUI.statusBox.hideStatus()
                    });
                } else {
                    location.reload();
                }
            }
        }.bind(this);
        viewTools.api.callController({
            module: "WorkSchedules",
            action: "save",
            dataType: 'text',
            async: false,
            dataPOST: {
                record: workschedule_id,
                status: 'closed',
                to_pdf: 1,
                sugar_body_only: 1
            },
            callback: callbackFunction
        });
    }

}
