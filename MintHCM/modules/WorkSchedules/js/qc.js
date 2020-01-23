$( document ).ready( function () {
   $( "#delegation_duration" ).change( function () {
      parseTimeNumberValue( $( "#delegation_duration" ) );
   } );
} );
