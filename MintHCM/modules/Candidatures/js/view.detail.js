convertToEmployee = {
   dialog_name: 'convertCandidatureToEmployeeDialog',
   module_name: 'Candidatures',
   action_name: 'convertToEmployee',
   LBL_CANCEL: viewTools.language.get( 'Candidatures', 'LBL_CANCEL_BUTTON' ),
   LBL_OK: viewTools.language.get( 'Candidatures', 'LBL_OK_BUTTON' ),
   LBL_CONVERT_DIALOG_TEXT: viewTools.language.get( 'Candidatures', 'LBL_CONVERT_DIALOG_TEXT' ),
   LBL_SUCCESS: viewTools.language.get( 'Candidatures', 'LBL_CONVERTING_CANDIDATURE_IN_PROGGRESS' ),
   LBL_FAIL: viewTools.language.get( 'Candidatures', 'LBL_FAILED_CONVERTING_CANDIDATURE' ),

   initialize: function () {
      var _this = this;
      var recordData = _this.getRecordData();

      if ( undefined === $( '#' + _this.dialog_name ).get( 0 ) ) {
         $( 'body' ).append( '<div id="' + _this.dialog_name + '" >' + _this.LBL_CONVERT_DIALOG_TEXT + '</div>' );
      }
      $( '#' + _this.dialog_name ).dialog( {
         hide: {
            effect: "fade",
            duration: 300
         },
         show: {
            effect: "fade",
            duration: 300
         },
         minWidth: 500,
         minHeight: 50,
         buttons: [
            {
               text: _this.LBL_OK,
               click: function () {
                  viewTools.GUI.statusBox.showStatus( _this.LBL_SUCCESS, 'success', 1000 );
                  _this.ajaxRequest( recordData.record_id, recordData.module_name, _this.LBL_FAIL );
               }
            },
            {
               text: _this.LBL_CANCEL,
               click: function () {
                  $( this ).dialog( "close" );
               }
            }
         ],
         close: function ( event, ui ) { }
      }
      );
   },

   getRecordData: function () {
      return {
         record_id: $( '#formDetailView input[name="record"]' ).val(),
         module_name: $( '#formDetailView input[name="module"]' ).val()
      };
   },

   ajaxRequest: function ( record_id, module_name, LBL_FAIL ) {
      var ajax_link = "index.php?action=" + this.action_name + "&module=" + module_name + "&record_id=" + record_id;
      $.get( ajax_link, function ( data ) {
      } ).done( function ( data ) {
         let id = data.match( /<id>(.*)<\/id>/ )[1];
         window.location.href = "index.php?module=Employees&return_module=Employees&action=DetailView&record=" + id;
      } ).fail( function () {
         viewTools.GUI.statusBox.showStatus( LBL_FAIL, 'error', 3500 );
         console.log( 'There was a problem handling Ajax request' );
      } );

   }
};


