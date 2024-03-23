$(document).ready(function () {
   checkComboDateStart();
   if ($('#EditView').parent().prop('className') == 'MintHCMPopup-body') {
      $('div .buttons #SAVE').hide();
      $('div .buttons #CANCEL').hide();
   }
});

function checkComboDateStart() {

   if (typeof combo_date_start !== 'undefined' && $("#date_start_hours").length > 0) {
      lockFields()
      setEvent();
      setDates();

   } else {
      setTimeout(checkComboDateStart, 1000);
   }
}
function setEvent() {

   if ($("#current_user_is_admin").val() == false) {
      lockAssignedUser();
   }
   $("#date_start_date").blur(function () {
      setWorkDate();

      recalculateSpendTime();
   });
   $("#date_end_hours, #date_end_minutes, #date_end_meridiem").change(function () {
      recalculateSpendTime();
   });
   $("#date_start_hours, #date_start_minutes, #date_start_meridiem").change(function () {

      recalculateSpendTime();
   });
   $("#spent_time").change(function () {
      convertClockTimeToFloatTime($("#spent_time"));
      parseTimeNumberValue($("#spent_time"));
      recalculateDateEnd();
   });
   $("#remaining_hours").change(function () {
      parseTimeNumberValue($("#remaining_hours"));
      setDoneRatioByRemainingHours();
   });
   $("#done_ratio").change(function () {
      setRemainingHoursByDoneRatio();
   });
   $("#spent_time").click(function () {
      $(this).select();
   });
   $("#remaining_hours").click(function () {
      $(this).select();
   });
}

if (!window.spentTimeSaveHandlerAlreadyInitialized) {
   viewTools.form.beforeSave(function (form_name) {
      var result_1 = validateWorkSchedule(form_name);
      var result_2 = validateDates(form_name);
      var result_6 = validateDescription(form_name);

      return result_1 && result_2 && result_6;
   });
   window.spentTimeSaveHandlerAlreadyInitialized = true;
}


function validateWorkSchedule(form_name) {
   var result = false;
   viewTools.api.callCustomApi({
      module: 'SpentTime',
      action: 'canLogTimeToPast',
      format: 'JSON',
      async: false,
      dataPOST: {
         workschedule_id: $("#" + form_name + " #workschedule_id").val(),
      },
      callback: function (data) {
         result = data.result;
         if (!result) {
            $.each(data.errors, function (i, v) {
               if ($.inArray(v, ['ERR_METHOD_ERROR_ISMYWORKSCHEDULEINCURRENTMONTH']) > -1) {
                  viewTools.GUI.fieldErrorMark($("#" + form_name + " #workschedule_name"), viewTools.language.get('SpentTime', 'LBL_ERR_CANNOT_ADD_SPENT_TIME_TO_THE_PAST_WORK_SCHEDULE'));
               }
            });
         }
      }
   });
   return result;
}

function validateDates(form_name) {
   var result_1 = true;
   var result_2 = true;
   var result_3 = true;
   var result_4 = true;

   var work_schedules_dates = getWorkSchedulesDates(form_name);
   if (work_schedules_dates !== false) {
      var work_schedule_datetime_start = work_schedules_dates.work_schedule_datetime_start;
      var work_schedule_datetime_end = work_schedules_dates.work_schedule_datetime_end;
      result_1 = validateWorkDateEqualsToDateStart(form_name);
      result_2 = validateDateFieldIDIsInWorkDates("#" + form_name + " #date_start", work_schedule_datetime_start, work_schedule_datetime_end);
      result_3 = validateDateFieldIDIsInWorkDates("#" + form_name + " #date_end", work_schedule_datetime_start, work_schedule_datetime_end);
      result_4 = validateUniqueSpentTime(form_name);
   }

   return result_1 && result_2 && result_3 && result_4;
}

function validateDescription(form_name) {
   var not_found_characters = true;
   var invalid_characters = ['\'', '"', '&'];
   var description = $('#' + form_name + ' #description').val();
   $(invalid_characters).each(function (index, character) {
      if (description.indexOf(character) !== -1) {
         viewTools.GUI.fieldErrorMark($("#" + form_name + " #description"), SUGAR.language.get('SpentTime', 'LBL_ERR_INVALID_CHAR_IN_DESCRIPTION'));
         not_found_characters = false;
         return false;
      }
   });
   return not_found_characters;
}

function getRecordID() {
    var record_id = '';
    if ($('#EditView input[name=record]').length > 0) {
        record_id = $("#EditView input[name=record]").val();
    } else if ($("#formDetailView input[name=record]").length > 0) {
        record_id = $("#formDetailView input[name=record]").val();
    } else if ($("#CloseButton").length > 0) {
        record_id = $( "#CloseButton" ).parent().parent().find( 'select' ).val();
    }
    return record_id;
}

function lockFields() {
   $('#work_date').prop("readonly", true);
   $('#work_date').next().hide();
   $("#employee_name").prop("readonly", true);
   $("#employee_name").next().hide();
   $("#employee_id").next().hide();
}

function lockAssignedUser() {
   $("#assigned_user_name").prop("readonly", true);
   $("#assigned_user_name").next().hide();
   $("#assigned_user_id").next().hide();
}

function setWorkDate() {
   $('#work_date').val($('#date_start_date').val());
}

function getWorkSchedulesDates(form_name) {
   var result = false;
   var workschedule_id = $("#" + form_name + " #workschedule_id").val();
   if (workschedule_id != '') {
      viewTools.api.callController({
         module: "WorkSchedules",
         action: "getWorkSchedulesDates",
         dataType: 'json',
         async: false,
         dataGET: {
            record: $("#" + form_name + " #workschedule_id").val()
         },
         callback: function (call_constroller_data) {
            if ($.isEmptyObject(call_constroller_data) == false) {
               result = call_constroller_data;
            }
         }
      });
   }
   return result;
}

function validateWorkDateEqualsToDateStart(form_name) {
   var result = true;
   var work_date = $("#" + form_name + " #work_date").val();
   var date_start_date = $("#" + form_name + " #date_start_date").val();
   if (work_date != date_start_date) {
      viewTools.GUI.fieldErrorMark($("#" + form_name + " #work_date"), SUGAR.language.get('SpentTime', 'LBL_ERR_WORK_DATE_NOT_EQUALS_TO_DATE_START'));
      result = false;
   }
   return result;
}

function validateDateFieldIDIsInWorkDates(date_field_selector, work_schedule_datetime_start, work_schedule_datetime_end) {
   var result = true;
   var spendtime_datetime_start = convertDateTimeStringToMoment($(date_field_selector).val());
   var work_datetime_start = convertDateTimeStringToMoment(work_schedule_datetime_start);
   var work_datetime_end = convertDateTimeStringToMoment(work_schedule_datetime_end);
   if (spendtime_datetime_start != false && work_datetime_start != false && work_datetime_end != false) {
      if (spendtime_datetime_start.format("YYYY-MM-DD") != work_datetime_start.format("YYYY-MM-DD") && spendtime_datetime_start.format("YYYY-MM-DD") != work_datetime_end.format("YYYY-MM-DD")) {
         viewTools.GUI.fieldErrorMark($(date_field_selector), SUGAR.language.get('SpentTime', 'LBL_ERR_DATE_ARE_NOT_BETWEEN_WORK_SCHEDULES_DATES'));
         result = false;
      }
   }
   return result;
}

function convertDateTimeStringToMoment(datetime_string) {
   var result = false;
   if (datetime_string != '') {
      var date_moment = moment(datetime_string, viewTools.date.getDateTimeFormat());
      if (date_moment._d.toString() != "Invalid Date") {
         result = date_moment;
      }
   }
   return result;
}

function validateUniqueSpentTime(form_name) {
   var result = true;
   var record_id = getRecordID();
   var assigned_user_id = $("#" + form_name + " #assigned_user_id").val();
   var date_start = $("#" + form_name + " #date_start").val();
   var date_end = $("#" + form_name + " #date_end").val();
   if (assigned_user_id != '' && date_start != '' && date_end != '') {
      viewTools.api.callController({
         module: "SpentTime",
         action: "isUniqueSpentTime",
         dataType: 'json',
         async: false,
         dataPOST: {
            record_id: record_id,
            assigned_user_id: assigned_user_id,
            date_start: date_start,
            date_end: date_end
         },
         callback: function (call_constroller_data) {
            if ($.isEmptyObject(call_constroller_data) == false && call_constroller_data.result == false) {
               result = false;
               viewTools.GUI.fieldErrorMark($("#" + form_name + " #date_end"), SUGAR.language.get('SpentTime', 'LBL_ERR_SPENT_TIME_NOT_UNIQUED'));
            }
         }
      });
   }
   return result;
}

function setDates() {
   var workschedule_id = $("#workschedule_id").val();
   if (getRecordID() == '') {
      if (workschedule_id != '') {
         viewTools.api.callController({
            module: "SpentTime",
            action: "getDate",
            dataType: 'json',
            async: false,
            dataGET: {
               record: workschedule_id
            },
            callback: function (call_constroller_data) {
               if ($.isEmptyObject(call_constroller_data) == false) {
                  function waitForDateTimeComboFields() {
                     if ($('#date_start_hours').length <= 0) {
                        window.setTimeout(waitForDateTimeComboFields.bind(this), 100);
                     } else {
                        updateDateStart(call_constroller_data.scheduleDateStart, call_constroller_data.scheduleDateLastMin);
                        updateDateEnd(call_constroller_data.scheduleDateEnd, call_constroller_data.scheduleDateEndMin);
                        setWorkDate();
                        setEvent();
                     }
                  };
                  waitForDateTimeComboFields();
               }
            }
         });
      } else {
         var current_date_time = moment();
         if (current_date_time._d.toString() != "Invalid Date") {
            var current_date_string = current_date_time.format(viewTools.date.getDateFormat());
            current_date_time.minutes(getDateMinutesOptionValue('date_start', current_date_time.minutes()));
            var current_date_time_string = current_date_time.format(viewTools.date.getDateTimeFormat());
            setDateTimeField('date_start', current_date_time_string);
            setWorkDate();
         }
      }
   }
}

function updateDateStart(schedule_date, schedule_date_last) {
   $('#date_start_date').val(schedule_date);
   if (schedule_date_last !== false) {
      schedule_date_last = updateDateStartMeridiem(schedule_date_last);
      var date_start_hours = parseInt(schedule_date_last.H);
      date_start_hours = (date_start_hours < 10 ? "0" : "") + date_start_hours.toString();
      $('#date_start_hours').val(date_start_hours);
      var date_start_minutes = parseInt(schedule_date_last.M);
      date_start_minutes = (date_start_minutes < 10 ? "0" : "") + date_start_minutes.toString();
      $('#date_start_minutes').val(date_start_minutes);
   }
   combo_date_start.update();
   recalculateSpendTime();
}

function updateDateStartMeridiem(schedule_date_last) {
   var date_start_meridiem = $('#date_start_meridiem');
   if (date_start_meridiem.length) {
      if (schedule_date_last.H == 0) {
         schedule_date_last.H = 12;
      }
      if (schedule_date_last.H > 12) {
         schedule_date_last.H -= 12;
         date_start_meridiem.val('pm');
      } else {
         date_start_meridiem.val('am');
      }
   }
   return schedule_date_last;
}

function updateDateEnd(schedule_date, schedule_date_end) {
   $('#date_end_date').val(schedule_date);
   schedule_date_end = updateDateEndMeridiem(schedule_date_end);
   schedule_date_end.M = getDateMinutesOptionValue('date_end', schedule_date_end.M);
   if (schedule_date_end.M == 60) {
      schedule_date_end.H++;
      schedule_date_end.M = 0;
   }
   var date_end_hours = parseInt(schedule_date_end.H);
   date_end_hours = (date_end_hours < 10 ? "0" : "") + date_end_hours.toString();
   $('#date_end_hours').val(date_end_hours);
   var date_end_minutes = parseInt(schedule_date_end.M);
   date_end_minutes = (date_end_minutes < 10 ? "0" : "") + date_end_minutes.toString();
   $('#date_end_minutes').val(date_end_minutes);
   combo_date_end.update();

   recalculateSpendTime();
}

function updateDateEndMeridiem(schedule_date_end) {
   var date_end_meridiem = $('#date_end_meridiem');
   if (date_end_meridiem.length) {
      if (schedule_date_end.H == 0) {
         schedule_date_end.H = 12;
      }
      if (schedule_date_end.H > 12) {
         schedule_date_end.H -= 12;
         date_end_meridiem.val('pm');
      } else {
         date_end_meridiem.val('am');
      }
   }
   return schedule_date_end;
}

function getDateMinutesOptionValue(date_id, schedule_date_end_m) {
   var schedule_date_end_minutes = parseInt(schedule_date_end_m);
   var date_end_minutes_values = $("#" + date_id + "_minutes > option").map(function () {
      if ($(this).val() != "") {
         return $(this).val();
      }
   });
   date_end_minutes_values.push("60");
   var real_date_end_minutes = date_end_minutes_values[0];
   $.each(date_end_minutes_values, function (key, value) {
      var int_value = parseInt(value);
      var next_int_value = (typeof date_end_minutes_values[key + 1] !== "undefined" ? parseInt(date_end_minutes_values[key + 1]) : 60);
      if (schedule_date_end_minutes > int_value && schedule_date_end_minutes <= next_int_value) {
         real_date_end_minutes = next_int_value;
      }
   });
   return real_date_end_minutes;
}

function recalculateSpendTime() {
   var date_start = moment($("#date_start").val(), viewTools.date.getDateTimeFormat());
   if ($('#EditView').parent().prop('className') == 'MintHCMPopup-body') {
      var date_start = moment($(".edit-view-row #date_start").val(), viewTools.date.getDateTimeFormat());
   }
   var date_end = moment($("#date_end").val(), viewTools.date.getDateTimeFormat());
   var spent_time = 0;
   if (date_start._d.toString() != "Invalid Date" && date_end._d.toString() != "Invalid Date") {
      spent_time = date_end.diff(date_start) / 1000 / 60 / 60;
      if (spent_time < 0) {
         spent_time = 0;
      }
   }
   $("#spent_time").val(spent_time);
   parseTimeNumberValue($("#spent_time"));
}

function convertClockTimeToFloatTime(element) {
   var element_value = element.val();
   if (element_value != '' && element_value.indexOf(':') > -1) {
      var clock_time_split = element_value.split(':');
      var result = (parseInt(clock_time_split[0]) + parseFloat(parseInt(clock_time_split[1].substr(0, 2)) / 60)).toFixed(2);
      element.val(result);
   }

}

function parseTimeNumberValue(element) {
   var precision = 2;
   var time_number = unformatNumber(element.val(), num_grp_sep, dec_sep);
   if (time_number == '' || isNaN(time_number)) {
      time_number = 0;
   }
   var formated_time_number = formatNumber(parseFloat(time_number).toFixed(precision), num_grp_sep, dec_sep, precision, precision);
   if (formated_time_number === dec_sep + '00') {
      formated_time_number = 0 + dec_sep + '00';
   }
   element.val(formated_time_number);
   return formated_time_number;
}

function setRemainingHoursByDoneRatio() {
   var done_ratio_element = $("#done_ratio");
   var remaining_hours_element = $("#remaining_hours");
   var remaining_hours = unformatNumber(remaining_hours_element.val(), num_grp_sep, dec_sep);
   if (done_ratio_element.val() == "100") {
      remaining_hours_element.val("0");
      parseTimeNumberValue(remaining_hours_element);
   }
}

function setDoneRatioByRemainingHours() {
   var done_ratio_element = $("#done_ratio");
   var remaining_hours_element = $("#remaining_hours");
   var remaining_hours = unformatNumber(remaining_hours_element.val(), num_grp_sep, dec_sep);
   if (remaining_hours == 0) {
      done_ratio_element.val("100");
   } else if (remaining_hours > 0 && done_ratio_element.val() == "100") {
      done_ratio_element.val("50");
   }
}

function recalculateDateEnd() {
   var new_date = moment($("#date_start").val(), viewTools.date.getDateTimeFormat());
   if ($('#EditView').parent().prop('className') == 'MintHCMPopup-body') {
      new_date = moment($(".edit-view-row #date_start").val(), viewTools.date.getDateTimeFormat());
   }
   if (new_date._d.toString() != "Invalid Date") {
      var spent_time = parseTimeNumberValue($("#spent_time"));
      var spent_time_float = unformatNumber(spent_time, num_grp_sep, dec_sep);
      new_date.minutes(new_date.minutes() + parseInt(spent_time_float * 60));
      new_date.minutes(getDateMinutesOptionValue('date_end', new_date.minutes()));
      var date_time_string = new_date.format(viewTools.date.getDateTimeFormat());
      setDateTimeField('date_end', date_time_string);
   }
}

function setDateTimeField(date_time_field_id, formatted_date_time) {
   var date_time = moment(formatted_date_time, viewTools.date.getDateTimeFormat());
   $('#' + date_time_field_id + '_date').val(date_time.format(viewTools.date.getDateFormat()));
   $('#' + date_time_field_id + '_hours').val(date_time.format("HH"));
   $('#' + date_time_field_id + '_minutes').val(date_time.format("mm"));
   if ($('#' + date_time_field_id + '_meridiem').length > 0) {
      if (viewTools.date.getTimeFormat().indexOf('a') > -1) {
         $('#' + date_time_field_id + '_meridiem').val(date_time.format("a"));
      } else if (viewTools.date.getTimeFormat().indexOf('A') > -1) {
         $('#' + date_time_field_id + '_meridiem').val(date_time.format("A"));
      }
   }
   if (date_time_field_id === 'date_start') {
      combo_date_start.update();
   } else if (date_time_field_id === 'date_end') {
      combo_date_end.update();
   }
   recalculateSpendTime();
}

function set_return_overload(data) {
   if (data.name_to_value_array.workschedule_id) {
      data.name_to_value_array.work_date = data.name_to_value_array.work_date.split(' ')[0];
   }
   set_return(data);
   if (data.name_to_value_array.workschedule_id) {
      setDates();
   }
}

QSCallbacksArray["EditView_workschedule_name"] = function (sqs) {
   var new_conditions = [{
      name: 'workschedules.status',
      op: 'not_equal',
      value: 'closed',
   }];

   viewTools.api.callCustomApi({
      module: 'SpentTime',
      action: 'getCurrentUserId',
      async: false,
      callback: function (data) {
         if (data) {
            new_conditions.push(
               {
                  name: 'workschedules.assigned_user_id',
                  op: 'equal',
                  value: data,
               });
         }
      }
   });

   _.each(new_conditions, function (value, key, list) {
      var index = _.findIndex(sqs.conditions, function (condition) {
         return condition.name != undefined && condition.name == value.name;
      });

      if (index < 0) {
         sqs.conditions.push(value);
      } else {
         sqs.conditions[index] = value;
      }
   });
   sqs.group = 'and';
};

