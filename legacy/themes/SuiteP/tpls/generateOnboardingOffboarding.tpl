<form id="<%= form_name %>" name="<%= form_name %>">
    <b><%= APP.LBL_GENERATEONBOARDINGOFFBOARDING_TEMPLATE %><span style="color:red">*</span>:</b>
    <div class="col-xs-12 col-sm-12 edit-view-field  yui-ac" type="parent" field="parent_name">
        <select name="parent_type" tabindex="0" id="parent_type" title="" class="vt_formulaSelector"
            onchange="document.<%= form_name %>.parent_name.value = &quot;&quot;;document.<%= form_name %>.parent_id.value = &quot;&quot;; changeParentQS( &quot;parent_name&quot; ); checkParentType( document.<%= form_name %>.parent_type.value, document.<%= form_name %>.btn_parent_name );"<%= hide_dropdown %>>
            <%= parent_type_options %>
        </select>
        <input type="text" name="parent_name" id="parent_name" class="vt_formulaSelector sqsEnabled yui-ac-input" tabindex="0" size="" autocomplete="off" value="<%= parent_name %>">
        <input type="hidden" class="vt_formulaSelector" name="parent_id" id="parent_id" value="<%= parent_id %>">
        <span class="id-ff multiple">
            <button type="button" name="btn_parent_name" id="btn_parent_name" tabindex="0" title="Wybierz"
                class="button firstChild" value="Wybierz"
                onclick="open_popup( <%= parent_type %>, 600, 400, &quot;&quot;, true, false, {&quot;call_back_function&quot;:&quot;viewTools.form.function.set_return&quot;,&quot;form_name&quot;:&quot;<%= form_name %>&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;parent_id&quot;,&quot;name&quot;:&quot;parent_name&quot;}}, &quot;single&quot;, true );"><span
                    class="suitepicon suitepicon-action-select"></span></button><button type="button"
                name="btn_clr_parent_name" id="btn_clr_parent_name" tabindex="0" title="" class="button lastChild"
                onclick="this.form.parent_name.value = ''; this.form.parent_id.value = ''; $( '#parent_name' ).blur();"
                value=""><span class="suitepicon suitepicon-action-clear"></span></button>
        </span>
    </div>
    <br />
    <b><%= APP.LBL_GENERATEONBOARDINGOFFBOARDING_EMPLOYEE_NAME %><span style="color:red">*</span>:</b>
    <div id="related-employee-0" type="relate" field="<%= relate_field_name %>_name_0">
        <input type="text" name="<%= relate_field_name %>_name0" class="vt_formulaSelector sqsEnabled yui-ac-input" tabindex="" id="<%= relate_field_name %>_name0" size="" value="<%= employee_name %>" title="" autocomplete="off">
        <input class="vt_formulaSelector" type="hidden" name="<%= relate_field_name %>_id0" id="<%= relate_field_name %>_id0" value="<%= employee_id %>">
        <span class="id-ff multiple">
            <button type="button" name="btn_<%= relate_field_name %>_name0" id="btn_<%= relate_field_name %>_name0" tabindex="" title="Wybierz użytkownika" class="button firstChild" value="Wybierz użytkownika" onclick="open_popup( '<%= relate_field_target_module %>', 600, 400, '&employee_status_advanced[]=Active&employee_status_advanced[]=during_termination', true, false,
                    {&quot;call_back_function&quot;:&quot;viewTools.form.function.set_return&quot;,&quot;form_name&quot;:&quot;<%= form_name %>&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;<%= relate_field_name %>_id0&quot;,&quot;name&quot;:&quot;<%= relate_field_name %>_name0&quot;}}, 'single', true );">
                <img src="themes/SuiteP/images/id-ff-select.png">
            </button>
            <button type="button" name="btn_clr_<%= relate_field_name %>_name0" id="btn_clr_<%= relate_field_name %>_name0" tabindex="" title="Wyczyść użytkownika" class="button lastChild" onclick="SUGAR.clearRelateField( this.form, '<%= relate_field_name %>_name0', '<%= relate_field_name %>_id0' );
                $( '#<%= relate_field_name %>_name,#<%= relate_field_name %>_id' ).blur();" value="Wyczyść użytkownika">
                <img src="themes/SuiteP/images/id-ff-clear.png">
            </button>
            <button type="button" name="btn_add_next_employee_field" id="btn_add_next_employee_field" tabindex="" title="Dodaj kolejnego użytkownika" class="button lastChild" onclick="addRelatedField();
                $( '#<%= relate_field_name %>_name,#<%= relate_field_name %>_id' ).blur();" value="Dodaj kolejnego użytkownika">
                <span class="suitepicon suitepicon-action-plus"></span>
            </button>
        </span> 
    </div>

    <div>
    <b><%= APP.LBL_GENERATEONBOARDINGOFFBOARDING_START_DATE %><span style="color:red">*</span>:</b>
    <div class="col-xs-12 col-sm-12 edit-view-field" type="datetimecombo" field="goo_date_start" style="margin-top: 7px">
        <table border="0" cellpadding="0" cellspacing="0" class="dateTime">
            <tbody>
                <tr valign="middle">
                    <td nowrap="" class="dateTimeComboColumn">
                        <input autocomplete="off" type="text" id="goo_date_start_date" class="datetimecombo_date" value="" size="11" maxlength="10" title="" tabindex="0" onblur="combo_goo_date_start.update();" onchange="combo_goo_date_start.update();">
                        <button type="button" id="goo_date_start_trigger" class="btn btn-danger" onclick="return false;">
                            <span class="suitepicon suitepicon-module-calendar" alt="Insert date"></span>
                        </button>
                    </td>
                    <td nowrap="" class="dateTimeComboColumn">
                        <div id="goo_date_start_time_section" class="datetimecombo_time_section"></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" class="DateTimeCombo" id="goo_date_start" name="goo_date_start" value="">
    </div>
</form>

<script type="text/javascript">

    var relatedEmployeeId = 0;

    function addRelatedField() {
        nextRelatedEmployeeId = relatedEmployeeId + 1; 
        if (relatedEmployeeId == 1) {
            $(".MintHCMPopup-container").css("max-height", "550px");
        }
        let html = '';
        html += '<div id="related-employee-' + nextRelatedEmployeeId + '" type="relate" field="<%= relate_field_name %>_name_' + nextRelatedEmployeeId + '">';
        html += '<input type="text" name="<%= relate_field_name %>_name' + nextRelatedEmployeeId + '" class="vt_formulaSelector sqsEnabled yui-ac-input" tabindex="" id="<%= relate_field_name %>_name' + nextRelatedEmployeeId + '" size="" value="<%= employee_name %>" title="" autocomplete="off">';
        html += '<input class="vt_formulaSelector" type="hidden" name="<%= relate_field_name %>_id' + nextRelatedEmployeeId + '" id="<%= relate_field_name %>_id' + nextRelatedEmployeeId + '" value="<%= employee_id %>">';
        html += '<span class="id-ff multiple">';
        html += '<button type="button" name="btn_<%= relate_field_name %>_name' + nextRelatedEmployeeId + '" id="btn_<%= relate_field_name %>_name' + nextRelatedEmployeeId + '" tabindex="" title="Wybierz użytkownika" class="button firstChild" value="Wybierz użytkownika" onclick="open_popup( \'<%= relate_field_target_module %>\', 600, 400, \'&employee_status_advanced[]=Active&employee_status_advanced[]=during_termination\', true, false, {&quot;call_back_function&quot;:&quot;viewTools.form.function.set_return&quot;,&quot;form_name&quot;:&quot;<%= form_name %>&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;<%= relate_field_name %>_id' + nextRelatedEmployeeId + '&quot;,&quot;name&quot;:&quot;<%= relate_field_name %>_name' + nextRelatedEmployeeId + '&quot;}}, \'single\', true );"><img src="themes/SuiteP/images/id-ff-select.png"></button>';
        html += '<button type="button" name="btn_clr_<%= relate_field_name %>_name' + nextRelatedEmployeeId + '" id="btn_clr_<%= relate_field_name %>_name' + nextRelatedEmployeeId + '" tabindex="" title="Wyczyść użytkownika" class="button lastChild" onclick="SUGAR.clearRelateField( this.form, \'<%= relate_field_name %>_name' + nextRelatedEmployeeId + '\', \'<%= relate_field_name %>_id' + nextRelatedEmployeeId + '\' );$( \'#<%= relate_field_name %>_name,#<%= relate_field_name %>_id\' ).blur();" value="Wyczyść użytkownika"><img src="themes/SuiteP/images/id-ff-clear.png"></button>';
        html += '<button type="button" name="btn_delete_employee_field" id="btn_delete_employee_field" tabindex="" title="Usuń użytkownika" class="button lastChild" onclick="deleteRelateField(' + nextRelatedEmployeeId + '); $( \'#<%= relate_field_name %>_name,#<%= relate_field_name %>_id\' ).blur();" value="Usuń użytkownika"><span class="suitepicon suitepicon-action-minus"></span></button>';
        html += '</span></div>';

        $('[id^="related-employee-"]').last().after(html);
        relatedEmployeeId++;
        customEnableQS();
    }

    function deleteRelateField(relatedEmployeeId) {
        $("#related-employee-" + relatedEmployeeId + "").remove();
    }

    function customEnableQS(noReload) {
        YAHOO.util.Event.onDOMReady(function() {
            if (typeof sqs_objects == 'undefined') {
                return;
            }
            var Dom = YAHOO.util.Dom;
            var qsFields = Dom.getElementsByClassName('sqsEnabled');
            for (var qsField in qsFields) {
                if (typeof qsFields[qsField] == 'function' || typeof qsFields[qsField].id == 'undefined') {
                    continue;
                }
                var form_id = qsFields[qsField].form.getAttribute('id');
                if (typeof form_id == 'object' && qsFields[qsField].form.getAttribute('real_id')) {
                    form_id = qsFields[qsField].form.getAttribute('real_id');
                }
                var qs_index_id = form_id + '_' + qsFields[qsField].name;
                if (typeof sqs_objects[qs_index_id] == 'undefined') {
                    let qsFieldName = qsFields[qsField].name;
                    this.qsFieldName = qsFieldName;
                    if (qsFieldName.includes("employee_name")) {
                        qs_index_id = form_id + '_employee_name';
                    } else {
                        qs_index_id = qsFieldName;
                    }
                    if (typeof sqs_objects[qs_index_id] == 'undefined') {
                        continue;
                    }
                }
                if (QSProcessedFieldsArray[qs_index_id] && qs_index_id != 'GenerateOnboardingOffboarding_employee_name') {
                    skipSTR = 'collection_0';
                    if (qs_index_id.lastIndexOf(skipSTR) != (qs_index_id.length - skipSTR.length)) {
                        continue;
                    }
                }
                var qs_obj = sqs_objects[qs_index_id];
                var loaded = false;
                if (!document.forms[qs_obj.form]) {
                    continue;
                }
                if (!document.forms[qs_obj.form].elements[qsFields[qsField].id].readOnly && qs_obj['disable'] != true) {
                    var combo_id = qs_obj.form + '_' + qsFields[qsField].id;
                    if (Dom.get(combo_id + "_results")) {
                        loaded = true
                    }
                    if (!loaded) {
                        QSProcessedFieldsArray[qs_index_id] = true;
                        qsFields[qsField].form_id = form_id;
                        var sqs = sqs_objects[qs_index_id];
                        var resultDiv = document.createElement('div');
                        resultDiv.id = combo_id + "_results";
                        Dom.insertAfter(resultDiv, qsFields[qsField]);
                        var fields = qs_obj.field_list.slice();
                        fields[fields.length] = "module";
                        var ds = new YAHOO.util.DataSource("index.php?",{
                            responseType: YAHOO.util.XHRDataSource.TYPE_JSON,
                            responseSchema: {
                                resultsList: 'fields',
                                total: 'totalCount',
                                fields: fields,
                                metaNode: "fields",
                                metaFields: {
                                    total: 'totalCount',
                                    fields: "fields"
                                }
                            },
                            connMethodPost: true
                        });
                        var forceSelect = !((qsFields[qsField].form && typeof (qsFields[qsField].form) == 'object' && qsFields[qsField].form.name == 'search_form') || qsFields[qsField].className.match('sqsNoAutofill') != null);
                        var search = new YAHOO.widget.AutoComplete(qsFields[qsField],resultDiv,ds,{
                            typeAhead: forceSelect,
                            forceSelection: forceSelect,
                            fields: fields,
                            sqs: sqs,
                            animSpeed: 0.25,
                            qs_obj: qs_obj,
                            inputElement: qsFields[qsField],
                            generateRequest: function(sQuery) {
                                var item_id = this.inputElement.form_id + '_' + this.inputElement.name;
                                this.sqs = updateSqsFromQSFieldsArray(item_id, this.sqs);
                                if (QSCallbacksArray[item_id]) {
                                    QSCallbacksArray[item_id](this.sqs);
                                }
                                var out = SUGAR.util.paramsToUrl({
                                    to_pdf: 'true',
                                    module: 'Home',
                                    action: 'quicksearchQuery',
                                    data: YAHOO.lang.JSON.stringify(this.sqs),
                                    query: decodeURIComponent(sQuery)
                                });
                                return out;
                            },
                            setFields: function(data, filter) {
                                this.updateFields(data, filter);
                            },
                            updateFields: function(data, filter) {
                                for (var i in this.fields) {
                                    for (var key in this.qs_obj.field_list) {
                                        if (this.fields[i] == this.qs_obj.field_list[key] && this.qs_obj.populate_list[key].match(filter)) {
                                            if (this.qs_obj.populate_list[key].includes("employee_")) {
                                                let id = this._elTextbox.id.split('employee_name')[1];
                                                if (this.qs_obj.populate_list[key].includes("employee_name")) {
                                                    this.qs_obj.populate_list[key] = "employee_name" + id;
                                                } else {
                                                    this.qs_obj.populate_list[key] = "employee_id" + id;
                                                }
                                            }
                                            var displayValue = data[i].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
                                            document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value = displayValue;
                                            SUGAR.util.callOnChangeListers(document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]);
                                        }
                                    }
                                }
                                SUGAR.util.callOnChangeListers(this._elTextbox);
                            },
                            clearFields: function() {
                                for (var key in this.qs_obj.field_list) {
                                    if (document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]) {
                                        document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value = "";
                                        SUGAR.util.callOnChangeListers(document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]);
                                    }
                                }
                                this.oldValue = "";
                            }
                        });
                        if (/^(billing_|shipping_)$/.exec(qsFields[qsField].name)) {
                            search.clearFields = function() {
                                for (var i in {
                                    name: 0,
                                    id: 1
                                }) {
                                    for (var key in this.qs_obj.field_list) {
                                        if (i == this.qs_obj.field_list[key] && document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]]) {
                                            document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value = "";
                                        }
                                    }
                                }
                            }
                            ;
                            search.setFields = function(data, filter) {
                                var label_str = '';
                                var label_data_str = '';
                                var current_label_data_str = '';
                                var label_data_hash = new Array();
                                for (var i in this.fields) {
                                    for (var key in this.qs_obj.field_list) {
                                        if (this.fields[i] == this.qs_obj.field_list[key] && document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]] && document.getElementById(this.qs_obj.populate_list[key] + '_label') && this.qs_obj.populate_list[key].match(filter)) {
                                            var displayValue = data[i].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
                                            var data_label = document.getElementById(this.qs_obj.populate_list[key] + '_label').innerHTML.replace(/\n/gi, '').replace(/<\/?[^>]+(>|$)/g, "");
                                            label_and_data = data_label + ' ' + displayValue;
                                            if (document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]] && !label_data_hash[data_label]) {
                                                label_str += data_label + ' \n';
                                                label_data_str += label_and_data + '\n';
                                                label_data_hash[data_label] = true;
                                                current_label_data_str += data_label + ' ' + document.forms[this.qs_obj.form].elements[this.qs_obj.populate_list[key]].value + '\n';
                                            }
                                        }
                                    }
                                }
                                if (label_str != current_label_data_str && current_label_data_str != label_data_str) {
                                    module_key = (typeof document.forms[form_id].elements['module'] != 'undefined') ? document.forms[form_id].elements['module'].value : 'app_strings';
                                    warning_label = SUGAR.language.translate(module_key, 'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM') + '\n\n' + label_data_str;
                                    if (confirm(warning_label)) {
                                        if (Dom.get('alt_checkbox')) {
                                            filter = Dom.get('alt_checkbox').checked ? filter : /^(?!alt)/;
                                        }
                                        this.updateFields(data, filter);
                                    }
                                } else {
                                    this.updateFields(data, filter);
                                }
                            }
                            ;
                        }
                        if (typeof (SUGAR.config.quicksearch_querydelay) != 'undefined') {
                            search.queryDelay = Number(SUGAR.config.quicksearch_querydelay);
                        }
                        search.itemSelectEvent.subscribe(function(e, args) {
                            var data = args[2];
                            var fields = this.fields;
                            this.setFields(data, /\S/);
                            if (typeof (this.qs_obj['post_onblur_function']) != 'undefined') {
                                collection_extended = new Array();
                                for (var i in fields) {
                                    for (var key in this.qs_obj.field_list) {
                                        if (fields[i] == this.qs_obj.field_list[key]) {
                                            collection_extended[this.qs_obj.field_list[key]] = data[i];
                                        }
                                    }
                                }
                                SUGAR.util.globalEval(this.qs_obj['post_onblur_function'] + '(collection_extended, this.qs_obj.id)');
                            }
                        });
                        search.textboxFocusEvent.subscribe(function() {
                            this.oldValue = this.getInputEl().value;
                        });
                        search.selectionEnforceEvent.subscribe(function(e, args) {
                            if (this.oldValue != args[1]) {
                                this.clearFields();
                            } else {
                                this.getInputEl().value = this.oldValue;
                            }
                        });
                        search.dataReturnEvent.subscribe(function(e, args) {
                            if (this.getInputEl().value.length == 0 && args[2].length > 0) {
                                var data = [];
                                for (var key in this.qs_obj.field_list) {
                                    data[data.length] = args[2][0][this.qs_obj.field_list[key]];
                                }
                                this.getInputEl().value = data[this.key];
                                this.itemSelectEvent.fire(this, "", data);
                            }
                        });
                        search.typeAheadEvent.subscribe(function(e, args) {
                            this.getInputEl().value = this.getInputEl().value.replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
                        });
                        if (typeof QSFieldsArray[combo_id] == 'undefined' && qsFields[qsField].id) {
                            QSFieldsArray[combo_id] = search;
                        }
                    }
                }
            }
        });
    }
</script>