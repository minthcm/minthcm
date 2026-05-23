YAHOO.util.Event.onContentReady('EditView', function () {
    if ($('#user_name').val() == '') {
        setDecisionMaker();
    }
    $('#assigned_user_name').blur(setDecisionMaker)
});

function setDecisionMaker() {
    var uid = $('#assigned_user_id').val();
    viewTools.api.callCustomApi({
        module: 'Ideas',
        action: 'getSupervisorName',
        dataPOST: {id: uid},
        callback: function (response) {
            viewTools.form.setValue($('#user_name'), response.name);
            viewTools.form.setValue($('#user_id'), response.id);
        }
    });
}