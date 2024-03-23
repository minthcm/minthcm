createPosition = {
   module_name: 'EmployeeRoles',
   action_name: 'createPosition',
   LBL_FAIL: viewTools.language.get( 'EmployeeRoles', 'LBL_FAILED_CREATING_POSITION' ),

   initialize: function () {
      var _this = this;
      var record_id = $( '#formDetailView input[name="record"]' ).val();
      var ajax_link = "index.php?action=" + _this.action_name + "&module=" + _this.module_name + "&record_id=" + record_id;
      $.get( ajax_link, function ( data ) {
      } ).done( function ( data ) {
         let id = data.match( /<id>(.*)<\/id>/ )[1];
         if ( !_.isEmpty( id ) ) {
            window.location.href = "index.php?module=Positions&action=DetailView&record=" + id;
         } else {
            viewTools.GUI.statusBox.showStatus( _this.LBL_FAIL, 'error', 3500 );
         }
      } ).fail( function () {
         viewTools.GUI.statusBox.showStatus( _this.LBL_FAIL, 'error', 3500 );
      } );
   },
};


