function acceptWorkPlan() {
    const button = new AcceptWorkPlanButton();
    button.click(this);
}

function acceptWorkPlanForDashlet(record_id, dashlet_id, page_url) {
    const button = new AcceptWorkPlanButton();
    button.clickFromDashlet(record_id, dashlet_id, page_url);
}

class AcceptWorkPlanButton extends Button {

    click(element) {
        super.click(element);
        this.acceptWorkPlan(this.getRecordID());
    }

    clickFromDashlet(record_id, dashlet_id, page_url) {
        this.acceptWorkPlan(record_id, dashlet_id, page_url);
    }

    acceptWorkPlan(workschedule_id, dashlet_id = null, page_url = null) {
        viewTools.GUI.statusBox.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'), 'info');
        const callbackFunction = function (call_constroller_data) {
            if (call_constroller_data == false || call_constroller_data == null) {
                console.error(call_constroller_data);
                viewTools.GUI.statusBox.hideStatus();
            } else {
                if (dashlet_id) {
                    SUGAR.mySugar.retrieveDashlet(dashlet_id, page_url, function () {
                        viewTools.GUI.statusBox.hideStatus()
                    });
                } else {
                    location.reload();
                }
            }
        }.bind(this)
        viewTools.api.callController({
            module: "WorkSchedules",
            action: "save",
            dataType: 'text',
            async: false,
            dataPOST: {
                record: workschedule_id,
                supervisor_acceptance: 'accepted',
                to_pdf: 1,
                sugar_body_only: 1
            },
            callback: callbackFunction
        });
    }

}
