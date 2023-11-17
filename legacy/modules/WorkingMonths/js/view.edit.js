$( document ).ready( function () {
   if ( $( '#year' ).val() == '' ) {
      var date = new Date();
      var month_names = [ "january", "february", "march", "april", "may", "june",
         "july", "august", "september", "october", "november", "december"
      ];
      $( '#year' ).val( date.getFullYear() );
      $( '#months' ).val( month_names[date.getMonth()] );
   }
   $( '#working_days' ).on( 'change', workingDaysChange );
   $( '#working_hours' ).on( 'change', workingHoursChange );
} );

function workingDaysChange( ) {
   let working_days = parseInt( $( '#working_days' ).val() );
   $( '#working_hours' ).val( isNaN( working_days ) ? '' : working_days * 8 );
}
function workingHoursChange( ) {
   let working_hours = parseInt( $( '#working_hours' ).val() );
   $( '#working_days' ).val( isNaN( working_hours ) ? '' : Math.round( working_hours ) / 8, 0 );
}
