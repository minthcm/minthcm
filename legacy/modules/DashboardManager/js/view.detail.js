function set_return_and_reload_subpanels() {
   var res = set_return_and_save_background.apply( {}, arguments );

   showSubPanel( 'users_locked_dashboards', undefined, true );
   showSubPanel( 'users_forced_tabs_dashboards', undefined, true );
   showSubPanel( 'users_one_time_default_dashboards', undefined, true );

   return res;
}

function loadUserDashboardConfirm() {
   var el = $( "#dm-confirm" );

   if ( !el.length ) {
      var title = SUGAR.language.get( 'DashboardManager', 'LBL_CONFIRM_TITLE' );
      var content = SUGAR.language.get( 'DashboardManager', 'LBL_CONFIRM_TEXT' );
      $( 'body' ).append( '<div id="dm-confirm" title="' + title + '"><p>' + content + '</p></div>' );
   }
   el = $( "#dm-confirm" );

   var lbl_yes = SUGAR.language.get( 'DashboardManager', 'LBL_CONFIRM_YES' );
   var lbl_cancel = SUGAR.language.get( 'DashboardManager', 'LBL_CONFIRM_CANCEL' );

   var buttons = {};
   buttons[lbl_yes] = function () {
      $( this ).dialog( "close" );
      var form = $( '#formDetailView' )[0];
      form.isSaveFromDetailView.value = true;
      form.customAction.value = 'loadDashboards';
      form.action.value = 'Save';
      form.return_module.value = 'DashboardManager';
      form.return_action.value = 'DetailView';
      form.return_id.value = $( '#formDetailView input[name=record]' ).val();
      form.submit();
   };
   buttons[lbl_cancel] = function () {
      $( this ).dialog( "close" );
   };
   el.dialog( {
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: buttons,
   } );
}