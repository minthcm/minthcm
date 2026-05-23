var appraisal_item_idx = 0;

function insertAppraisalItem(inline_appraisal_item_data) {

    if (typeof inline_appraisal_item_data !== 'undefined') {
        inline_appraisal_item_data = JSON.parse(inline_appraisal_item_data);
        if (_.isEmpty(inline_appraisal_item_data.description)) {
            inline_appraisal_item_data.description = '';
        }
    } else {
        inline_appraisal_item_data = {
            id: '',
            name: '',
            value: '',
            parent_type: 'Goals',
            parent_name: '',
            parent_id: '',
            description: '',
        };
    }

    var appraisal_item_div = $('<div id="appraisal_item_row' + appraisal_item_idx + '"></div>')[0];
    $("#lineItems")[0].append(appraisal_item_div);

    var createNewField = function (cols = 3) {
        return $('<div class="col-xs-12 col-sm-' + cols + ' edit-view-row-item"></div>')[0];
    }

    var newField = createNewField(4);
    appraisal_item_div.append(newField);
    newField.append($("<input type='hidden' name='inline_appraisal_item_id[" + appraisal_item_idx + "]' id='inlinetechnology_id" + appraisal_item_idx + "' value='" + inline_appraisal_item_data.id + "'>")[0]);

    const optionsData = SUGAR.language.get('app_list_strings', 'appraisal_subject_list');
    let options = `<select name="parent_type[${appraisal_item_idx}]" tabindex="0" id="parent_type${appraisal_item_idx}" title="" onchange="onParentChange(${appraisal_item_idx})">`;
    for (let key in optionsData) {
        options += `<option label="${optionsData[key]}" value="${key}">${optionsData[key]}</option>`;
    }
    options += `</select>`;
    newField.append($(options)[0]);

    newField.append($('<input type="text" name="parent_name[' + appraisal_item_idx + ']" id="parent_name' + appraisal_item_idx + '" class="sqsEnabled yui-ac-input" tabindex="0" size="" autocomplete="off" value="' + inline_appraisal_item_data.parent_name + '">')[0]);
    newField.append($('<input type="hidden" name="parent_id[' + appraisal_item_idx + ']" id="parent_id' + appraisal_item_idx + '" value="' + inline_appraisal_item_data.parent_id + '">')[0]);
    newField.append($("<span class='id-ff'><button title='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_TITLE') + "' accessKey='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_KEY') + "' type='button' tabindex='116' class='button' value='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_LABEL') + "' onclick='openAppraisalItemPopup(" + appraisal_item_idx + ");'><img src='themes/" + SUGAR.themes.theme_name + "/images/id-ff-select.png' alt='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_LABEL') + "'></button></span>")[0]);

    $('select[name="parent_type[' + appraisal_item_idx + ']"]').val(inline_appraisal_item_data.parent_type);

    sqs_objects["EditView_parent_name[" + appraisal_item_idx + "]"] = {
        "form": "EditView",
        "method": "query",
        "modules": [$('select[name="parent_type[' + appraisal_item_idx + ']"]').val()],
        "field_list": ["name", "id"],
        "populate_list": ["parent_name[" + appraisal_item_idx + "]", "parent_id[" + appraisal_item_idx + "]"],
        "required_list": ["parent_id[" + appraisal_item_idx + "]"],
        "conditions": [{
            "name": "name",
            "op": "like_custom",
            "end": "%",
            "value": ""
        }],
        "order": "name",
        "limit": "30",
        "no_match_text": "No Match",
        "post_onblur_function": "",
    };

    enableQS(true);

    var newField = createNewField(2);
    appraisal_item_div.append(newField);
    newField.append($('<select name="value[' + appraisal_item_idx + ']" id="value' + appraisal_item_idx + '" title=""><option label="" value=""></option><option label="N/A" value="n_a">N/A</option><option label="1" value="1">1</option><option label="2" value="2">2</option><option label="3" value="3">3</option><option label="4" value="4">4</option><option label="5" value="5">5</option></select>')[0]);

    $('select[name="value[' + appraisal_item_idx + ']"]').val(inline_appraisal_item_data.value);

    var newField = createNewField(5);
    appraisal_item_div.append(newField);
    newField.append($('<textarea name="appraisal_item_description[' + appraisal_item_idx + ']" id="appraisal_item_description' + appraisal_item_idx + '" class="description"  rows="1" cols="40" title="" tabindex="0" style="width: 100%;">' + inline_appraisal_item_data.description + '</textarea>')[0]);

    var newField = createNewField(1);
    appraisal_item_div.append(newField);
    newField.append($("<span class='id-ff'><button type='button' class='button' value='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "' tabindex='116' onclick='removeAppraisalItem(" + appraisal_item_idx + ")'><img src='themes/" + SUGAR.themes.theme_name + "/images/id-ff-clear.png' alt='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "'></button></span>")[0]);

    appraisal_item_div.append($("<input type='hidden' name='appraisal_item_deleted[" + appraisal_item_idx + "]' id='appraisal_item_deleted" + appraisal_item_idx + "' value='0'>")[0]);
    appraisal_item_div.append($('<div class="clear"></div>')[0]);
    appraisal_item_div.append($('<div id="appraisal_item_error' + appraisal_item_idx + '" style="display: none" class="required appraisal_item' + appraisal_item_idx + '"></div>')[0]);
    appraisal_item_div.append($('<div class="clear"></div>')[0]);

    return appraisal_item_idx++;
}

function onParentChange(item_idx) {
    sqs_objects["EditView_parent_name[" + item_idx + "]"].modules = [$('select[name="parent_type[' + item_idx + ']"]').val()];
    $('input[name="parent_name[' + item_idx + ']"]').val('');
    $('input[name="parent_id[' + item_idx + ']"]').val('');
}

function removeAppraisalItem(item_idx) {
    $('#appraisal_item_deleted' + item_idx)[0].value = '1';
    $('#appraisal_item_row' + item_idx).hide();
}

function openAppraisalItemPopup(item_idx) {
    var popup_request_data = {
        "call_back_function": "catchPopupReturn",
        "form_name": "EditView",
        "field_to_name_array": {
            "id": "parent_id" + item_idx,
            "name": "parent_name" + item_idx,
        },
        "passthru_data": {
            "id": "parent_id" + item_idx,
            "name": "parent_name" + item_idx,
        },
    };

    open_popup($('select[name="parent_type[' + item_idx + ']"]').val(), 800, 850, '', true, true, popup_request_data, 'Select');
}

function catchPopupReturn(reply_data) {
    set_return(reply_data);
}

function validateAppraisalItems() {
    var result = true;

    for (var idx = 0; idx < appraisal_item_idx; idx++) {
        var error = $('#appraisal_item_error' + idx);
        error.html("");
        error.css('display', 'none');
    }

    for (var idx = 0; idx < appraisal_item_idx; idx++) {
        var deleted = $('#appraisal_item_deleted' + idx)[0].value;

        if (deleted == 1) {
            continue;
        }

        var error = $('#appraisal_item_error' + idx);
        var appraisal_type = $('select#type')[0].value;
        var type = $('#parent_type' + idx)[0].value;
        var name = $('#parent_name' + idx)[0].value;
        var id = $('#parent_id' + idx)[0].value;
        if (name.length == 0 || id.length == 0) {
            error.html(SUGAR.language.get('app_strings', 'LBL_APPRAISAL_ITEM_ERROR'));
            error.css('display', 'block');
            result = false;
        } else if (appraisal_type == 'recruiting' && type != "Competencies") {
            error.html(SUGAR.language.get('app_strings', 'LBL_APPRAISAL_ITEM_TYPE_ERROR'));
            error.css('display', 'block');
            result = false;
        }
    }

    return result;
}

viewTools.form.beforeSave(function () {
    return validateAppraisalItems();
}, true);
