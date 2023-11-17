viewTools.form.beforeSave(function () {
    var selector = document.getElementById("currency_id_select");
    var index = selector.selectedIndex;
    var currency_id = selector.options[index].value;    
    var values = viewTools.form.getFormValues();
    if(values.delegation_id === undefined) {
        delegation_id = $('input[name=delegation_id]').val();
    } else {
        delegation_id = values.delegation_id;
    }
    
    viewTools.api.callCustomApi({
        module: 'Costs',
        action: 'validateSelectedCurrency',
        async: false,
        dataPOST: {
            delegation_id: delegation_id,
            currency_id: currency_id,
            transportation_id: values.transportation_id,
        },
        callback: function (response) {
            if (response == false) {
                viewTools.GUI.fieldErrorMark($('#currency_id_select'), viewTools.language.get('Costs', 'LBL_CURRENCY_LOCALE_NOT_MATCHING'));
                currency_accepted = false;
            }
            else currency_accepted = true;
        }
    });
    return currency_accepted;
}, false)


