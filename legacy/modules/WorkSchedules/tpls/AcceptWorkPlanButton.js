function getRecordID() {
   var record_id = '';
   if ( $( "#formDetailView > input[name=record]" ).length > 0 ) {
      record_id = $( "input[name=record]" ).val();
   } else {
      record_id = $( "#AcceptWorkPlanButton" ).parent().parent().find( 'select' ).val();
   }
   return record_id;
}

function acceptWorkPlan() {
   viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_SAVING' ), 'info' );
   var workschedule_id = getRecordID();
   viewTools.api.callController( {
      module: "WorkSchedules",
      action: "save",
      dataType: 'text',
      async: false,
      dataPOST: {
         record: workschedule_id,
         supervisor_acceptance: 'accepted',
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

function acceptWorkPlanForDashlet(record_id, dashlet_id, page_url) {
    viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_SAVING' ), 'info' );
    if(record_id == undefined || !record_id){
        viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_ERROR' ), 'error', 3000 );
        console.error('Record ID is not defined');
        return false;
    }
    var workschedule_id = record_id;
    viewTools.api.callController( {
       module: "WorkSchedules",
       action: "save",
       dataType: 'text',
       async: false,
       dataPOST: {
          record: workschedule_id,
          supervisor_acceptance: 'accepted',
          to_pdf: 1,
          sugar_body_only: 1
       },
       callback: function ( call_constroller_data ) {
          if ( call_constroller_data == false || call_constroller_data == null ) {
            viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_ERROR' ), 'error', 3000 );
             console.error( call_constroller_data );
          } else {
            viewTools.GUI.statusBox.hideStatus();
            SUGAR.mySugar.retrieveDashlet( dashlet_id, page_url );
          }
       }
    } );
 }