var listeneresAttached = false;

$(document).ready(function () {
   if (!listeneresAttached) {

      $("#type").change(function () {
         updateDuration();
         updateDateEndByDuration();
      });

      $("#duration_hours").keyup(function () {
         roundValue($("#duration_hours"));
         updateDateEndByDuration();
      });
      $("#duration_hours").change(function () {
         roundValue($("#duration_hours"));
         updateDateEndByDuration();
      });
      $("#duration_minutes").change(function () {
         updateDateEndByDuration();
      });

      $("#date_start_date, #date_end_date").blur(function () {
         updateDurationByDateEnd();
      });
      $("#date_start_hours, #date_start_minutes, #date_start_meridiem").change(function () {
         updateDurationByDateEnd();
      });
      $("#date_start_date").blur(function () {
         updateDurationByDateEnd();
      });
      $("#date_end_hours, #date_end_minutes, #date_end_meridiem").change(function () {
         updateDurationByDateEnd();
      });

      listeneresAttached = true;
   }
   var redirected_from_calendar = $("#redirected_from_calendar").val();
   if (redirected_from_calendar == 1) {
      var date_start = convertDateFieldToMoment("date_start");
      var date_end = convertDateFieldToMoment("date_end");
      if (date_start != false && date_end != false) {
         var diff_minutes = getMomentsDiffInSeconds(date_start, date_end) / 60;
         if (diff_minutes == 30) {
            $('#previous_diff_minutes').val(30);
            updateDuration();
            updateDateEndByDuration();
         } else {
            updateDurationByDateEnd();
         }
      }
   }
});


function updateDuration() {
   if (getRecordID() == '') {
      var type = $('#type').val();
      var duration_hours = 8;
      switch (type) {
         case "overtime":
            duration_hours = 1;
            break;
      }
      $('#duration_hours').val(duration_hours);
      $('#duration_minutes').val(0);
   }
}

function checkMinutesFromStartToEnd() {
   var date_start_hours = parseInt($("#date_start_hours").val());
   var date_start_minutes = parseInt($("#date_start_minutes").val());
   var date_end_hours = parseInt($("#date_end_hours").val());
   var date_end_minutes = parseInt($("#date_end_minutes").val());
   var start_minutes = date_start_hours * 60 + date_start_minutes;
   var end_minutes = date_end_hours * 60 + date_end_minutes;

   return end_minutes - start_minutes;
}

function roundValue(element) {
   var date_start = getDateObject($("#date_start_date").val() + " " + $("#date_start_hours").val() + ":00:00");
   var date_start_max = getDateObject($("#date_start_date").val() + " 23:59:59");
   var hours_limit = Math.floor((date_start_max - date_start) / 1000 / 60 / 60);
   if (element.val() < 0) {
      element.val("0");
   }
}

function updateDurationByDateEnd() {
   var date_start = convertDateFieldToMoment("date_start");
   if ($('#date_end_date').val() == "") {
      var date_end = date_start.clone();
      delete date_end._i;
      date_end.add(8, 'h');
   } else {
      var date_end = convertDateFieldToMoment("date_end");
   }
   if (date_start != false && date_end != false) {
      var diff_seconds = getMomentsDiffInSeconds(date_start, date_end);
      var hours = Math.floor(diff_seconds / 60 / 60);
      var minutes = Math.ceil(diff_seconds / 60 % 60 / 5) * 5;
      $('#duration_hours').val(hours);
      $('#duration_minutes').val(minutes);
      updateDateEndByDuration();
   }
}

function updateDateEndByDuration() {
   var date_start = getDateObject($('#date_start').val());
   var duration = parseInt($('#duration_hours').val()) * 60 + parseInt($('#duration_minutes').val());
   if (isNaN(duration)) {
      duration = 0;
   }
   if (date_start instanceof Date) {
      var date_end = new Date(date_start);
      date_end.setMinutes(date_end.getMinutes() + duration);
      setDateTimeField('date_end', date_end);
   }
}

function setDateTimeField(date_time_field_id, formatted_date_time) {
   var date_time = null;
   if (typeof formatted_date_time == 'object') {
      date_time = moment(formatted_date_time);
   } else if (typeof formatted_date_time == 'string') {
      date_time = moment(formatted_date_time, viewTools.date.getDateTimeFormat());
   }
   if (date_time._d.toString() != "Invalid Date") {
      $('#' + date_time_field_id + '_date').val(date_time.format(viewTools.date.getDateFormat()));
      $('#' + date_time_field_id + '_minutes').val(date_time.format("mm"));
      if ($('#' + date_time_field_id + '_meridiem').length > 0) {
         if (viewTools.date.getTimeFormat().indexOf('a') > -1) {
            $('#' + date_time_field_id + '_meridiem').val(date_time.format("a"));
         } else if (viewTools.date.getTimeFormat().indexOf('A') > -1) {
            $('#' + date_time_field_id + '_meridiem').val(date_time.format("A"));
         }

         $('#' + date_time_field_id + '_hours').val(date_time.format("hh"));
      } else {
         $('#' + date_time_field_id + '_hours').val(date_time.format("HH"));
      }
      if (date_time_field_id === 'date_start') {
         combo_date_start.update();
      } else if (date_time_field_id === 'date_end') {
         combo_date_end.update();
      }
   }
}
