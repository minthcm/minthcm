$(document).ready(function () {
    var redirected_from_calendar = $("#redirected_from_calendar").val();
    if (redirected_from_calendar == 1) {
        createActivitySelectDialog();
    }

    $("#delegation_duration")
        .change(function () {
            parseTimeNumberValue($("#delegation_duration"));
        })
        .change();

    if (
        $("#type").val() == "office"
        && $('#record').val() == ''
    ) {
        setDefaultWorkPlace();
    }
    $("#type").change(() => {
        if ($("#type :selected").val() === "office") {
            setDefaultWorkPlace();
        }
    });
    setDefaultWorkPlace();
});

if (!window.workSchedulerSaveHandlerAlreadyInitialized) {
    viewTools.form.beforeSave(function () {
        viewTools.GUI.fieldErrorUnmark();
        var result_1 = validateUniqueWorkSchedules();
        var result_2 = validateDatesEndAfterStart();
        var result_3 = validateWorkScheduleLength();
        var result_4 = validateWorkScheduleCreatedByPeriodicity();

        return result_1 && result_2 && result_3 && result_4;
    });
    window.workSchedulerSaveHandlerAlreadyInitialized = true;
}

function getRecordID() {
    var record_id = "";
    if ($("input[name=record]").length > 0) {
        record_id = $("input[name=record]").val();
    }
    return record_id;
}

function isUserAdmin() {
    return $("#current_user_is_admin").val() == true;
}

function parseTimeNumberValue(element) {
    var precision = 2;
    var time_number = unformatNumber(element.val(), num_grp_sep, dec_sep);
    if (time_number == "" || isNaN(time_number)) {
        time_number = 0;
    }
    var formated_time_number = formatNumber(
        parseFloat(time_number).toFixed(precision),
        num_grp_sep,
        dec_sep,
        precision,
        precision
    );
    if (formated_time_number === dec_sep + "00") {
        formated_time_number = 0 + dec_sep + "00";
    }
    element.val(formated_time_number);
    return formated_time_number;
}

function setDateTimeField(date_time_field_id, formatted_date_time) {
    var date_time = moment(
        formatted_date_time,
        viewTools.date.getDateTimeFormat()
    );
    $("#" + date_time_field_id + "_date").val(
        date_time.format(viewTools.date.getDateFormat())
    );
    $("#" + date_time_field_id + "_hours").val(date_time.format("HH"));
    $("#" + date_time_field_id + "_minutes").val(date_time.format("mm"));
    if ($("#" + date_time_field_id + "_meridiem").length > 0) {
        if (viewTools.date.getTimeFormat().indexOf("a") > -1) {
            $("#" + date_time_field_id + "_meridiem").val(
                date_time.format("a")
            );
        } else if (viewTools.date.getTimeFormat().indexOf("A") > -1) {
            $("#" + date_time_field_id + "_meridiem").val(
                date_time.format("A")
            );
        }
    }
    if (date_time_field_id === "date_start") {
        combo_date_start.update();
    } else if (date_time_field_id === "date_end") {
        combo_date_end.update();
    }
}

function validateUniqueWorkSchedules() {
    var result = true;
    var record_id = getRecordID();
    var assigned_user_id = $("#assigned_user_id").val();
    var date_start = $("#date_start").val();
    var date_end = $("#date_end").val();
    if (assigned_user_id != "" && date_start != "" && date_end != "") {
        viewTools.api.callController({
            module: "WorkSchedules",
            action: "isUniqueWorkSchedule",
            dataType: "json",
            async: false,
            dataPOST: {
                assigned_user_id: assigned_user_id,
                date_start: date_start,
                date_end: date_end,
                record_id: record_id,
            },
            callback: function (call_constroller_data) {
                if (
                    $.isEmptyObject(call_constroller_data) == false &&
                    call_constroller_data.result == false
                ) {
                    result = false;
                    viewTools.GUI.fieldErrorMark(
                        $("#date_end"),
                        SUGAR.language.get(
                            "WorkSchedules",
                            "LBL_WORKSCHEDULES_FOR_THIS_PERIOD_ALREADY_EXISTS"
                        )
                    );
                }
            },
        });
    }
    return result;
}

function validateWorkScheduleLength() {
    var result = true;
    var date_start = convertDateFieldToMoment("date_start");
    var date_end = convertDateFieldToMoment("date_end");
    if (date_start != false && date_end != false) {
        var diff_seconds = getMomentsDiffInSeconds(date_start, date_end);
        if (diff_seconds > 24 * 60 * 60) {
            result = false;
            viewTools.GUI.fieldErrorMark(
                $("#date_end"),
                SUGAR.language.get(
                    "WorkSchedules",
                    "LBL_WORKSCHEDULES_LENGHT_OVER_ONE_DAY"
                )
            );
        } else if (diff_seconds <= 0) {
            result = false;
            viewTools.GUI.fieldErrorMark(
                $("#duration_hours"),
                SUGAR.language.get(
                    "WorkSchedules",
                    "LBL_WORKSCHEDULES_DURATION_LESS_OR_EQUAL_ZERO"
                )
            );
        }
        if (date_start.hours() == 0 && date_end.hours() == 0) {
            result = false;
            viewTools.GUI.fieldErrorMark(
                $("#duration_hours"),
                SUGAR.language.get(
                    "WorkSchedules",
                    "LBL_WORKSCHEDULES_FORBIDDEN_HOURS"
                )
            );
        }
    }
    return result;
}

function validateDatesEndAfterStart() {
    var result = true;
    var date_start = convertDateFieldToMoment("date_start");
    var date_end = convertDateFieldToMoment("date_end");
    if (date_start != false && date_end != false) {
        var diff_seconds = getMomentsDiffInSeconds(date_start, date_end);
        if (diff_seconds < 0) {
            result = false;
            viewTools.GUI.fieldErrorMark(
                $("#date_end"),
                SUGAR.language.get(
                    "WorkSchedules",
                    "LBL_WORKSCHEDULES_DATE_END_BEFORE_START"
                )
            );
        }
    }
    return result;
}

function getMomentsDiffInSeconds(moment_start, moment_end) {
    return moment_end.diff(moment_start) / 1000;
}

function convertDateFieldToMoment(field_id) {
    var result = false;
    if ($("#" + field_id).length == 1) {
        var date_moment = moment(
            $("#" + field_id).val(),
            viewTools.date.getDateTimeFormat()
        );
        if (date_moment._d.toString() != "Invalid Date") {
            result = date_moment;
        }
    }
    return result;
}

function createActivitySelectDialog() {
    $("body").prepend(
        '<div id="activity-select-dialog"><p><b>' +
            SUGAR.language.get("WorkSchedules", "LBL_ACTIVITY_SELECT_DIALOG") +
            "</b></p></div>"
    );
    var return_module = $("#return_module").val();
    $("#activity-select-dialog").dialog({
        draggable: false,
        position: { my: "center top+5%", at: "center top", of: "#pagecontent" },
        width: 400,
        buttons: [
            {
                text: SUGAR.language.get("WorkSchedules", "LBL_CREATE_MEETING"),
                click: function () {
                    var date_start = convertDateFieldToMoment("date_start");
                    if ($("#previous_diff_minutes").val() == 30) {
                        var date_end = date_start.clone();
                        delete date_end._i;
                        date_end.add(1, "h");
                        var date_formatted = date_end.format(
                            viewTools.date.getDateTimeFormat()
                        );
                        var url =
                            "index.php?module=Meetings&action=EditView&return_module=" +
                            return_module +
                            "&date_start=" +
                            $("#date_start").val() +
                            "&date_end=" +
                            date_formatted +
                            "&assigned_user_id=" +
                            $("#assigned_user_id").val();
                    } else {
                        var url =
                            "index.php?module=Meetings&action=EditView&return_module=" +
                            return_module +
                            "&date_start=" +
                            $("#date_start").val() +
                            "&date_end=" +
                            $("#date_end").val() +
                            "&assigned_user_id=" +
                            $("#assigned_user_id").val();
                    }
                    window.location.assign(url);
                },
            },
            {
                text: SUGAR.language.get("WorkSchedules", "LBL_CREATE_CALL"),
                click: function () {
                    var url =
                        "index.php?module=Calls&action=EditView&return_module=" +
                        return_module +
                        "&date_start=" +
                        $("#date_start").val() +
                        "&assigned_user_id=" +
                        $("#assigned_user_id").val();
                    window.location.assign(url);
                },
            },
        ],
        open: function (event, ui) {
            setTimeout("$('#activity-select-dialog').dialog('close')", 4000);
        },
    });
}

function validateWorkScheduleCreatedByPeriodicity() {
    let result = true;
    let type = $("select[name=repeat_type]").val();
    let repeat_end_type = document.querySelector('input[name="repeat_end_type"]:checked')?.value ?? "";
    if (type !== "") {
        var data = {
            type: type,
            interval: $("select[name=repeat_interval]").val(),
            count: repeat_end_type=="number" ? $("input[name=repeat_count]").val() : "",
            until: repeat_end_type=="date" ? $("input[name=repeat_until]").val(): "",
            dow: $("#repeat_dow").val(),
            date_start: $("#date_start").val(),
            duration_hours: $("#duration_hours").val(),
            duration_minutes: $("#duration_minutes").val(),
            record_id: $("input[name=record]").val(),
            assigned_user_id: $("#assigned_user_id").val(),
        };
        viewTools.api.callCustomApi({
            module: "WorkSchedules",
            action: "checkWorkScheduleCreatedByPeriodicity",
            async: false,
            dataPOST: {
                data: data,
            },
            callback: function (data) {
                if (data) {
                    var error_label =
                        SUGAR.language.get(
                            "WorkSchedules",
                            "LBL_WORKSCHEDULES_PERIODICITY_CREATE_ERROR"
                        ) +
                        " " +
                        data;
                    viewTools.GUI.fieldErrorMark($("#date_start"), error_label);
                    result = false;
                }
            },
        });
    }
    return result;
}

function setDefaultWorkPlace() {
    if ($('#workplace_name').val() || $('#workplace_id').val()) {
        return;
    }
    viewTools.api.callCustomApi({
        module: "WorkSchedules",
        action: "getActiveWorkplaces",
        dataPOST: {
            assigned_user_id: $("#assigned_user_id").val(),
            date_start: $('#date_start').val(), 
            date_end: $('#date_end').val(), 
        },
        callback: function (result) {
            if (result.length === 1) {
                $('#workplace_name').val(result[0].name);
                $('#workplace_id').val(result[0].id);
            }
        },
    });
}
