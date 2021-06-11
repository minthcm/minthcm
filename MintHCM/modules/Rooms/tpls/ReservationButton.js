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
   $( "#ReservationButton" ).click( function () {
      beforeCreateResource();
   } );
} );

function getRecordID() {
   var record_id = '';
   if ( $( "#formDetailView > input[name=record]" ).length > 0 ) {
      record_id = $( "input[name=record]" ).val();
   } else {
      record_id = $( "#ReservationButton" ).parent().parent().find( 'select' ).val();
   }
   return record_id;
}

function isUserAdmin() {
   return $( "#current_user_is_admin" ).val() == true;
}

function beforeCreateResource() {
   var dialog = $( '#alert_dialog' );
   var dialog_buttons = {};
   dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_YES' )] = function () {
      $( this ).dialog( "close" );
      createResource();
      location.reload();
   };
   dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_NO' )] = function () {
      $( this ).dialog( "close" );
   };
   dialog.html( '<p>' + SUGAR.language.get( 'app_strings', 'LBL_CREATE_RESOURCE_CONFIRM' ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
}

function createResource(){
   viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_SAVING' ), 'info' );
   var room_id = getRecordID();
   viewTools.api.callController( {
      module: "Rooms",
      action: "save_resource",
      dataType: 'json',
      async: false,
      dataPOST: {
         record: room_id,
      },
      callback: function ( call_constroller_data ) {
         if ( call_constroller_data == false || call_constroller_data == null ) {
            console.error( call_constroller_data );
         } else {
            location.reload();
         }
      }
   });
}
