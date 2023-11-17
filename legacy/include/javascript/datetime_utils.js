function convertDateFieldToMoment(field_id) {
    var result = false;
    if ($("#" + field_id).length == 1) {
        var date_moment = moment($("#" + field_id).val(), viewTools.date.getDateTimeFormat());
        if (date_moment._d.toString() != "Invalid Date") {
            result = date_moment;
        }
    }
    return result;
}

function getMomentsDiffInSeconds(moment_start, moment_end) {
    return moment_end.diff(moment_start) / 1000;
}