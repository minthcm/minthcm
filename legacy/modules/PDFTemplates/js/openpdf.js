function openPDF( record, module ) {
   var x = document.getElementById( "templateselect" );
   var template = x.value;
   window.open( "index.php?module=PDFGenerator&action=Popup&query=true&sugar_body_only=1"
           + "&record=" + record
           + "&template=" + template
           + "&module_name=" + module, "mywindow" );

}
// load available templates for current module into select
function getTemplates() {
   //try for old way of passing module in uri
   var mod = getURI( 'module' );

   //for new ajax uri parameters

   if ( (mod == null) || (mod == '') ) {
      var win_loc = window.location;
      var loc = win_loc.hash;
      var loc_2 = decodeURIComponent( loc );
      mod = getURIFrom( 'module', loc_2 );
   }
   var params = "module=PDFTemplates&action=templates&sugar_body_only=1&rmodule=" + mod;
   var callback = {
      success: function ( o ) {
         var items = JSON.parse( o.responseText );
         var list = document.getElementById( "templateselect_span" );
         if ( items != null ) {
            if ( items.length > 1 ) {
               list.innerHTML = '<select name="templateselect" id="templateselect" style="vertical-align:middle;margin-right:-3px;"></select>';
               list = document.getElementById( "templateselect" );
               for ( e in items ) {
                  var opt = document.createElement( "option" );
                  opt.value = items[e].value;
                  opt.text = items[e].text;
                  list.options.add( opt );
               }
            } else {
               list.innerHTML = '<input type="hidden" name="templateselect" id="templateselect" style="vertical-align:middle;margin-right:-3px;" >';
               list = document.getElementById( "templateselect" );
               list.value = items[0].value;
            }
         } else {
            list.innerHTML = '<input type="hidden" name="templateselect" id="templateselect" style="vertical-align:middle;margin-right:-3px;" >';

         }

      },
      failure: function ( o ) {/*failure handler code*/
      },
      argument: [ ]
   };
   var obj = YAHOO.util.Connect.asyncRequest( 'POST', 'index.php', callback, params );
}
function getURIFrom( name, str ) {
   name = name.replace( /[\[]/, "\\\[" ).replace( /[\]]/, "\\\]" );
   var regexS = "[\\?&]" + name + "=([^&#]*)";
   var regex = new RegExp( regexS );
   var results = regex.exec( str );
   if ( results == null )
      return "";
   else
      return results[1];
}
function getURI( name )
{
   var str = window.location.href;
   return getURIFrom( name, str );
}
//window.onload = getTemplates();

//SUGAR.util.doWhen("document.getElementById('templateselect') != null",getTemplates());
YAHOO.util.Event.onContentReady( "templateselect_span", function () {
   getTemplates();

} ); 