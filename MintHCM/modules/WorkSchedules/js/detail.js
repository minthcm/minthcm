$( document ).ready( function () {
   removeButtonsByStatus();
} );

function removeButtonsByStatus() {
   if ( $( "#status" ).val() == 'closed' ) {
      removeButtonsByAdmin();
   }
}

function removeButtonsByAdmin() {
   if ( $( "#current_user_is_admin" ).val() == false ) {
      $( '#tab-actions' ).remove();
   }
}
