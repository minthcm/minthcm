MassUpdateManager = {
   massConfirmation: function () {
      this.sendRequest( 'WSMassConfirmation' );
   },
   sendRequest: function ( action_name ) {
      if ( sugarListView.get_checks_count() > 0 && sugarListView.get_checks() ) {
         var module_name = $( 'form#search_form' ).get( 0 ).module.value;
         if ( document.MassUpdate.select_entire_list.value == "1" ) {
            var query = encodeURIComponent( document.MassUpdate.current_query_by_page.value );
            var url_ = "index.php?module=" + module_name + "&action=" + action_name + "&sugar_body_only=1&entire_list=1&encoded_query=" + query;
         } else {
            var url_ = "index.php?module=" + module_name + "&action=" + action_name + "&sugar_body_only=1&ids=" + document.MassUpdate.uid.value;
         }
         $.ajax( {
            type: "POST",
            async: true,
            url: url_,
            beforeSend: function ( ) {
               viewTools.GUI.statusBox.showStatus( viewTools.language.get( $( 'form#search_form' ).get( 0 ).module.value, 'INFO_SCHEDULE_IN_PROGRESS_' + action_name.toUpperCase() ), 'notice' );
            },
            success: function ( msg ) {
               viewTools.GUI.statusBox.showStatus( viewTools.language.get( $( 'form#search_form' ).get( 0 ).module.value, 'INFO_SCHEDULE_COMPLETED_' + action_name.toUpperCase() ), 'success', 5000 );
            },
            error: function ( jqXHR, exception ) {
               viewTools.GUI.statusBox.showStatus( viewTools.language.get( $( 'form#search_form' ).get( 0 ).module.value, 'ERR_SCHEDULE_' + action_name.toUpperCase() ), 'error', 5000 );
               console.error( jqXHR.responseText );
            }
         } );
      } else {
         viewTools.GUI.statusBox.showStatus( viewTools.language.get( 'app_strings', 'LBL_LISTVIEW_NO_SELECTED' ), 'error', 3000 );
         return false;
      }
   },
}
