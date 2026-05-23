var Survey = (function ( $ ) {
   var bindToggle = function ( event ) {
      event.preventDefault();

      var clickedElement = $( event.target );
      var resultTable = $( '#' + clickedElement.data( 'question-id' ) + 'List' );

      resultTable.slideToggle( 500, function () {
         clickedElement.text( resultTable.is( ':visible' ) ? viewTools.language.get( 'Surveys', 'LBL_HIDE_RESPONSES' ) : viewTools.language.get( 'Surveys', 'LBL_SHOW_RESPONSES' ) ); //viewTools #51331
      } );
   };

   var showHide = function ( elements ) {
      $( elements ).each( function ( index, element ) {
         $( element ).on( 'click', bindToggle );
      } );
   };

   var result = {};
   result.showHide = showHide;

   return result;
})( jQuery );
