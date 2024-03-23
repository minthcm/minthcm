$( document ).ready( function () {
   $( '<div id="alert_dialog"></div>' ).appendTo( 'body' ).dialog( {
      modal: true,
      title: "",
      zIndex: 10000,
      autoOpen: false,
      width: 'auto',
      height: 'auto',
      resizable: false
   } );
   $( "#CloseButton" ).click( function () {
      beforeClosePlan();
   } );
} );

function getTimePanel() {
   return TimePanel.instances[0];
}


function getRecordID() {
   var record_id = '';
   if ( $( "#formDetailView > input[name=record]" ).length > 0 ) {
      record_id = $( "input[name=record]" ).val();
   } else {
      record_id = $( "#CloseButton" ).parent().parent().find( 'select' ).val();
   }
   return record_id;
}

function isUserAdmin() {
   return $( "#current_user_is_admin" ).val() == true;
}

function beforeClosePlan() {
   var dialog = $( '#alert_dialog' );
   var workschedule_id = getRecordID();
   if ( workschedule_id == '' || workschedule_id == null || workschedule_id == undefined ) {
      var dialog_buttons = {};
      dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_OK' )] = function () {
         $( this ).dialog( "close" );
      };
      dialog.html( '<p>' + SUGAR.language.get( 'app_strings', 'LBL_CHOOSE_PLAN' ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
   } else {
      var dialog_buttons = {};
      dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_YES' )] = function () {
         $( this ).dialog( "close" );
         var planType = $( '#type' ).val() || (getTimePanel().taskman._currentPlans.filter( function ( i ) {
            return i.id == getRecordID();
         } ))[0].type;
         var dontCheck = [ 'holiday', 'sick', 'occasional_leave', 'overtime', 'excused_absence', 'leave_at_request' ].indexOf( planType ) >= 0;
         if ( dontCheck || checkIfCanBeClosed() ) {
            closePlan();
         }
      };
      dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_NO' )] = function () {
         $( this ).dialog( "close" );
      };
      dialog.html( '<p>' + SUGAR.language.get( 'app_strings', 'LBL_CLOSE_PLAN_CONFIRM' ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
   }
}

function checkScheduleName( workschedule_id ) {
   var result = "null";
   viewTools.api.callController( {
      module: "WorkSchedules",
      action: "checkScheduleName",
      dataType: 'text',
      async: false,
      dataPOST: {
         id: workschedule_id
      },
      callback: function ( call_constroller_data ) {
         result = call_constroller_data;
      }
   } );
   return result;
}


function checkIfCanBeClosed() {
   var result = true;
   var dialog = $( '#alert_dialog' );
   var workschedule_id = getRecordID() || getTimePanel().taskman.$planSelect.val();
   var schedule_name = checkScheduleName( workschedule_id );
   viewTools.api.callController( {
      module: "WorkSchedules",
      action: "checkIfCanBeClosed",
      dataType: 'json',
      async: false,
      dataPOST: {
         id: workschedule_id
      },
      callback: function ( call_constroller_data ) {
         if ( call_constroller_data != "1" ) {
            var dialog_buttons = {};
            dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_OK' )] = function () {
               $( this ).dialog( "close" );
            };
            if(call_constroller_data == "2"){
                dialog.html( '<p>' + viewTools.language.get( 'WorkSchedules', 'ERR_SPENT_TIMES_DO_NOT_OVERLAP_WITH_WORK_SCHEDULE' ).replace( '{name}', schedule_name ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
             }
             if(call_constroller_data == "3"){
                 dialog.html( '<p>' + viewTools.language.get( 'WorkSchedules', 'ERR_WORKPLACE_IS_REQUIRED' ).replace( '{name}', schedule_name ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
              }
             else if(call_constroller_data == "4"){
                dialog.html( '<p>' + viewTools.language.get( 'WorkSchedules', 'ERR_WORKPLACE_IS_NOT_ACTIVE' ).replace( '{name}', schedule_name ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
             }
            result = false;
         }
      }
   } );
   return result;
}

function closePlan() {
   viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_SAVING' ), 'info' );
   var workschedule_id = getRecordID();
   viewTools.api.callController( {
      module: "WorkSchedules",
      action: "save",
      dataType: 'text',
      async: false,
      dataPOST: {
         record: workschedule_id,
         status: 'closed',
         to_pdf: 1,
         sugar_body_only: 1
      },
      callback: function ( call_constroller_data ) {
         if ( call_constroller_data == false || call_constroller_data == null ) {
            console.error( call_constroller_data );
         } else {
            location.reload();
         }
      }
   } );
}
