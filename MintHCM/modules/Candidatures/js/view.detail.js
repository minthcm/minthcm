convertToEmployee = {
    dialog_name: 'convertCandidatureToEmployeeDialog',
    module_name: 'Candidatures',
    action_name: 'convertToEmployee',

    LBL_CANCEL: viewTools.language.get('Candidatures', 'LBL_CANCEL_BUTTON'),
    LBL_OK: viewTools.language.get('Candidatures', 'LBL_OK_BUTTON'),

    LBL_ALERT_TITLE: viewTools.language.get('Candidatures', 'LBL_ALERT_TITLE'),
    LBL_ALERT_LOGIN: viewTools.language.get('Candidatures', 'LBL_ALERT_LOGIN'),
    LBL_ALERT_NOTE: viewTools.language.get('Candidatures', 'LBL_ALERT_NOTE'),
    LBL_CREATE_USER: viewTools.language.get('Candidatures', 'LBL_CREATE_USER'),
    LBL_CREATE_EMPLOYEE: viewTools.language.get('Candidatures', 'LBL_CREATE_EMPLOYEE'),
    LBL_ALERT_CREATE_USER: viewTools.language.get('Candidatures', 'LBL_ALERT_CREATE_USER'),
    LBL_ALERT_CREATE_Employee: viewTools.language.get('Candidatures', 'LBL_ALERT_CREATE_Employee'),
    LBL_FAIL: viewTools.language.get('Candidatures', 'LBL_FAILED_CONVERTING_CANDIDATURE'),
    LBL_INFO: viewTools.language.get('Candidatures', 'LBL_INFO'),
    initialize: function () {
        const _this = this;
        const recordData = _this.getRecordData();

        const popupBody = `
            <script>
            $('input[type=radio][name=convertType]').change(function() {
                viewTools.GUI.fieldErrorUnmark()
                if (this.value == 'createUser') {
                    $('#input_login').show()
                    $('#note').text("${_this.LBL_ALERT_CREATE_USER}")
                }
                else if (this.value == 'createEmployee') {
                    $('#input_login').hide()
                    $('#note').text("${_this.LBL_ALERT_CREATE_Employee}")
                 }
            });
            </script>
            <div id="div_radio" >
            <span id="info">${_this.LBL_INFO}</span>
            <form name="type">
            <input type="radio" name="convertType" value="createUser" id="createUser"/><labal for="createUser">${_this.LBL_CREATE_USER}</label>  
            <input type="radio" name="convertType" value="createEmployee" id="createEmployee"/><labal for="createEmployee">${_this.LBL_CREATE_EMPLOYEE} </label>  
            </form>
            </div>
            <div id="input_login" class="Input_login">
            <b>${_this.LBL_ALERT_LOGIN}</b>
            <form name="login">
            <input type="text" name="MintHCMPopup_login" id="MintHCMPopup_login"/>
            <form>
            </div>
            <span id="note"></span>
            <style>
                .Input_login{
                    display: none;
                }
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
            let login = $("#MintHCMPopup_login").val();
            const convertType = $("input[name='convertType']:checked").val();

            if (convertType == undefined) {
                viewTools.GUI.fieldErrorMark($('#createUser'), viewTools.language.get('Candidatures', 'LBL_ERROR_INPUT_RADIO'));
            } else if (convertType == "createUser") {
                if(login == ""){
                    viewTools.GUI.fieldErrorUnmark();
                    viewTools.GUI.fieldErrorMark($("#MintHCMPopup_login"), viewTools.language.get('Candidatures', 'LBL_ERROR_LOGIN'));
                } else if (_this.checkUserDuplicate(login)) {
                    viewTools.GUI.fieldErrorUnmark();
                    viewTools.GUI.fieldErrorMark($("#MintHCMPopup_login"), viewTools.language.get('Candidatures', 'LBL_ERROR_LOGIN_DUPLICATE'));
                } else {
                    _this.ajaxRequest(recordData.record_id, recordData.module_name, login, _this.LBL_FAIL, convertType);
                }
            }
            else if (convertType == "createEmployee") {
                login = "";
                _this.setStatusHiredAndRejected(recordData.record_id);
                _this.ajaxRequest(recordData.record_id, recordData.module_name, login, _this.LBL_FAIL, convertType);
            }
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

    checkUserDuplicate: function(login){
        var result = "";
        viewTools.api.callCustomApi( {
            module: 'Users',
            action: 'checkUserDuplicate',
            async:false,
            dataPOST: {
                login: login,
            },
            callback: function ( data ) {
                result = data;
            }
         } );
         return result;
    },

    getRecordData: function () {
        return {
            record_id: $('#formDetailView input[name="record"]').val(),
            module_name: $('#formDetailView input[name="module"]').val()
        };
    },

    setStatusHiredAndRejected: function(record_id){
        viewTools.api.callCustomApi( {
            module: 'Candidatures',
            action: 'setStatusHiredAndRejected',
            async:false,
            dataPOST: {
                record_id: record_id,
            },
         } );
    },

    ajaxRequest: function (record_id, module_name, login, LBL_FAIL, convert_type) {
        const ajax_link = `index.php?sugar_body_only=1&action=${this.action_name}&module=${module_name}&record_id=${record_id}&login=${login}`;

        $.ajax({
            type: "GET",
            url: ajax_link,
            async: false,
            success: function (id) {
                if (convert_type == "createEmployee") {
                    window.location.href = `index.php?module=Employees&return_module=Employees&action=DetailView&record=${id}`;
                } else if (convert_type == "createUser") {
                    window.location.href = `index.php?module=Users&return_module=Users&action=DetailView&record=${id}`;
                }
            },
            error: function (jqXHR, exception) {
                viewTools.GUI.statusBox.showStatus(LBL_FAIL, 'error', 3500);
                console.log('There was a problem handling Ajax request');
            }
        });
    },

};


