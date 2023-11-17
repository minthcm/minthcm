$(document).ready(function () {
    $( '<div id="alert_dialog"></div>' ).appendTo( 'body' ).dialog( {
        modal: true,
        title: "",
        zIndex: 10000,
        autoOpen: false,
        width: 'auto',
        height: 'auto',
        resizable: false
     } );
    var dialog = $( '#alert_dialog' );
    var dialog_buttons = {};
    dialog_buttons[SUGAR.language.get( 'app_strings', 'LBL_DIALOG_YES' )] = function (){
        $( this ).dialog( "close" );
        location.reload();
    }
    dialog.html( '<p>' + SUGAR.language.get( 'app_strings', 'LBL_CLOSE_PLAN_CONFIRM' ) + '</p>' ).dialog( {buttons: dialog_buttons} ).dialog( 'open' ).show();
});