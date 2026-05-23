function undoWorkPlan() {
    const button = new UndoAcceptWorkPlanButton();
    button.undoWorkPlan(this);
}

class UndoAcceptWorkPlanButton extends Button {

    undoWorkPlan(elem) {
        viewTools.GUI.statusBox.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'), 'info');
        var workschedule_id = this.getRecordID();
        viewTools.api.callController({
            module: "WorkSchedules",
            action: "save",
            dataType: 'text',
            async: false,
            dataPOST: {
                record: workschedule_id,
                supervisor_acceptance: 'wait',
                to_pdf: 1,
                sugar_body_only: 1
            },
            callback: function (call_constroller_data) {
                if (call_constroller_data == false || call_constroller_data == null) {
                    console.error(call_constroller_data);
                } else {
                    location.reload();
                }
            }
        });
    }
}

