$( document ).ready( function () {
   var LBL_SUCCESS = viewTools.language.get( 'Administration', 'LBL_REPAIR_PRIVATE_GROUP_STATUS_BOX_SUCCESS' );
   var LBL_FAIL = viewTools.language.get( 'Administration', 'LBL_REPAIR_PRIVATE_GROUP_STATUS_BOX_FAIL' );

   $( '#private_group_repair' ).on( 'click', function ( event ) {
      event.preventDefault();
      repairPrivateGroupsViaAjax();
   } );

   function repairPrivateGroupsViaAjax() {
      $.get( "index.php?module=SecurityGroups&action=repair", function ( data ) {
      } ).done( function ( data ) {
         if ( data.indexOf( 'Repair Private Security Groups job added to Queue.' ) != -1 ) {
            viewTools.GUI.statusBox.showStatus( LBL_SUCCESS, 'success', 1500 );
         } else {
            viewTools.GUI.statusBox.showStatus( LBL_FAIL, 'error', 1500 );
         }
      } ).fail( function () {
         viewTools.GUI.statusBox.showStatus( LBL_FAIL, 'error', 1500 );
         console.log( 'There was a problem handling Ajax request' );
      } );
   }
} )