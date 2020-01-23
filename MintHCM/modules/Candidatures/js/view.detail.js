convertToEmployee = {
    dialog_name: 'convertCandidatureToEmployeeDialog',
    module_name: 'Candidatures',
    action_name: 'convertToEmployee',

    LBL_CANCEL: viewTools.language.get('Candidatures', 'LBL_CANCEL_BUTTON'),
    LBL_OK: viewTools.language.get('Candidatures', 'LBL_OK_BUTTON'),

    LBL_ALERT_TITLE: viewTools.language.get('Candidatures', 'LBL_ALERT_TITLE'),
    LBL_ALERT_LOGIN: viewTools.language.get('Candidatures', 'LBL_ALERT_LOGIN'),
    LBL_ALERT_NOTE: viewTools.language.get('Candidatures', 'LBL_ALERT_NOTE'),

    LBL_FAIL: viewTools.language.get('Candidatures', 'LBL_FAILED_CONVERTING_CANDIDATURE'),

    initialize: function () {
        const _this = this;
        const recordData = _this.getRecordData();

        const popupBody = `
            <b>${_this.LBL_ALERT_LOGIN}</b>
            <input type="text" id="MintHCMPopup_login"/>
            <span>${_this.LBL_ALERT_NOTE}</span>
            <style>
                .MintHCMPopup-body{
                    display: grid;
                    grid-gap: 10px;
                    padding: 20px 30px;
                }
                #MintHCMPopup_status{
                    display: none;
                    color: red;
                }
            </style>
        `;

        function callbackButtonOK() {
            const login = $("#MintHCMPopup_login").val();
            _this.ajaxRequest(recordData.record_id, recordData.module_name, login, _this.LBL_FAIL);
        }

        function callbackButtonCancel() {
            MintHCMPopup.close();
        }

        const popupButtons = [
            {
                text: _this.LBL_OK,
                click: callbackButtonOK
            },
            {
                text: _this.LBL_CANCEL,
                click: callbackButtonCancel
            }
        ];

        let popup = MintHCMPopup(
            _this.LBL_ALERT_TITLE,
            popupBody,
            popupButtons,
            {}
        );
    },

    getRecordData: function () {
        return {
            record_id: $('#formDetailView input[name="record"]').val(),
            module_name: $('#formDetailView input[name="module"]').val()
        };
    },

    ajaxRequest: function (record_id, module_name, login, LBL_FAIL) {
        const ajax_link = `index.php?action=${this.action_name}&module=${module_name}&record_id=${record_id}&login=${login}`;
        $.get(ajax_link, function (data) {
        }).done(function (data) {
            const id = data.match(/<id>(.*)<\/id>/)[1];
            window.location.href = `index.php?module=Employees&return_module=Employees&action=DetailView&record=${id}`;
        }).fail(function () {
            viewTools.GUI.statusBox.showStatus(LBL_FAIL, 'error', 3500);
            console.log('There was a problem handling Ajax request');
        });
    }

};


