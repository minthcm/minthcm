function setCurrentCalendarDate() {
   var view = calendar.getViewName();
   var calendarDate = calendar.getDate();
   var date = "";
   switch ( view ) {
      case "day":
         date = calendarDate.getDate() + "-" + (calendarDate.getMonth() + 1) + "-" + calendarDate.getFullYear();
         break;
      case "week":
         var dateRangeStart = calendar.getDateRangeStart();
         var dateRangeEnd = calendar.getDateRangeEnd();
         date = dateRangeStart.getDate() + "-" + (dateRangeStart.getMonth() + 1) + "-" + dateRangeStart.getFullYear() + " ~ " + dateRangeEnd.getDate() + "-" + (dateRangeEnd.getMonth() + 1) + "-" + dateRangeEnd.getFullYear();
         break;
      case "month":
         date = (calendarDate.getMonth() + 1) + "-" + calendarDate.getFullYear();
         break;
   }
   $( 'div#currentCalendarDate' ).html( date );
}

function setCurrentCalendar() {
   if ( typeof calendarList !== "undefined" ) {
      var calendar_id = $( 'select#resources' ).val();
      calendarList.forEach( function ( cal ) {
         if ( cal.id == calendar_id ) {
            cal.checked = true;
         } else {
            cal.checked = false;
         }
         calendar.toggleSchedules( cal.id, !cal.checked, false );
      } );
      calendar.render( true );
   }
}

function scheduleUpdate( event ) {
   if ( typeof event === 'undefined' || event.schedule.isReadOnly ) {
      return;
   } else if ( typeof event.triggerEventName !== 'undefined' && event.triggerEventName === "click" ) {
      SUGAR.ajaxUI.loadContent( "index.php?module=Reservations&action=EditView&record=" + event.schedule.id + "&return_module=ReservationsCalendar&return_action=index" );
      return;
   }

   var schedule = event.schedule;
   var eventStart = event.start;
   var eventEnd = event.end;

   if ( eventStart.getDay() === schedule.start.getDay() && eventEnd.getDay() !== schedule.end.getDay() ) {
      eventEnd.setHours( schedule.end.getHours() );
      eventEnd.setMinutes( schedule.end.getMinutes() );
   }

   if ( eventEnd.getMinutes() === 59 ) {
      eventEnd.setMinutes( 45 );
   }

   var startTime = moment( eventStart.toDate() ).format( viewTools.date.getDateTimeFormat() );
   var endTime = moment( eventEnd.toDate() ).format( viewTools.date.getDateTimeFormat() );

   viewTools.api.callCustomApi( {
      module: 'Reservations',
      action: 'updateReservation',
      dataPOST: {
         reservation_id: schedule.id,
         starting_date: startTime,
         ending_date: endTime
      },
      callback: function ( data ) {
         if ( data ) {
            calendar.updateSchedule( schedule.id, schedule.calendarId, {
               start: eventStart,
               end: eventEnd
            } );
         }
      }
   } );
}

function scheduleDelete( event ) {
   var schedule = event.schedule;
   viewTools.api.callCustomApi( {
      module: 'Reservations',
      action: 'deleteReservation',
      dataPOST: {
         reservation_id: schedule.id
      },
      callback: function ( data ) {
         if ( data ) {
            calendar.deleteSchedule( schedule.id, schedule.calendarId );
         }
      }
   } );
}

function createReservation( event ) {
   if ( typeof event === 'undefined' ) {
      if ( typeof event.guide !== 'undefined' ) {
         event.guide.clearGuideElement();
      }
      return;
   }
   var view = calendar.getViewName();
   var resource_id = $( '#resources' ).val();
   var resource_name = $( '#resources option:selected' ).text();
   var location = 'index.php?module=Reservations&action=EditView&resource_id=' + resource_id + '&resource_name=' + resource_name + "&return_module=ReservationsCalendar&return_action=index";
   var eventStart = moment( event.start.toDate() );
   var eventEnd = moment( event.end.toDate() );
   if ( eventEnd.minutes() == 59 ) {
      eventEnd.minutes( 45 );
   }
   if ( view === 'month' || (view !== 'month' && typeof event.isAllDay !== 'undefined' && event.isAllDay) ) {
      eventStart.hours( 8 );
      eventStart.minutes( 0 );
      eventEnd.hours( 16 );
      eventEnd.minutes( 0 );
   }
   var startTime = eventStart.format( viewTools.date.getDateTimeFormat() );
   var endTime = eventEnd.format( viewTools.date.getDateTimeFormat() );
   location += '&starting_date=' + startTime + '&ending_date=' + endTime;
   SUGAR.ajaxUI.loadContent( location );
}

YAHOO.util.Event.onContentReady( 'calendar', function () {
   calendar_fdow = parseInt( calendar_fdow ); /* 0 - sunday, ..., 6 - saturday */
   calendar = new tui.Calendar( '#calendar', {
      defaultView: 'month',
      calendars: calendarList,
      useDetailPopup: true,
      month: {
         startDayOfWeek: calendar_fdow
      },
      week: {
         startDayOfWeek: calendar_fdow
      }
   } );
   calendar.on( {
      'beforeUpdateSchedule': scheduleUpdate,
      'beforeDeleteSchedule': scheduleDelete,
      'beforeCreateSchedule': createReservation
   } );
   if ( typeof reservationList !== "undefined" ) {
      calendar.createSchedules( reservationList );
   }
   $( 'input.tuiCalendar' ).click( setCurrentCalendarDate );
   $( 'select#resources' ).change( setCurrentCalendar );
   setCurrentCalendarDate();
   setCurrentCalendar();
} );
