function clearUrl( url ) {
   let count = (url.match( /:\/\//g ) || [ ]).length;
   let res = url;
   if ( count > 1 ) {
      let pos = url.indexOf( '://' );
      res = url.substr( pos + 3 );
   }
   return res;
}

$( document ).ready( function ()
{
   $( '#preview_label_span' ).append( '<br>' );
   $( '#preview_label' ).append( '<br>' );

   preview = document.getElementById( 'preview' );
   let url = clearUrl( preview.value );

   if ( document.baseURI.indexOf( "https://" ) !== -1 ) {
      url = url.replace( 'http://', 'https://' );
   }
   preview.value = url;
   changeToHTML();
} );

function changeToPDF()
{
   var m_name = document.getElementById( 'relatedmodule' ).value;
   var template_id = document.getElementsByName( 'record' )[0].value;
   td = document.getElementById( 'preview' ).parentNode;
   td.children[1].src = "index.php?module=KTemplates&action=Preview&query=true&sugar_body_only=1&template=" + template_id + "&module_name=" + m_name;

   var label = 'Switch to HTML';
   if ( typeof SUGAR.language !== 'undefined' ) {
      label = SUGAR.language.get( 'KTemplates', 'LBL_SWITCH_TO_HTML' );
   }

   if ( document.getElementById( 'preview_label_span' ) != null )
   {
      $( '#preview_label_span' ).append( '<a href="#" onclick="changeToHTML();$(this).remove();" >' + label + '</a>' );
   } else if ( document.getElementById( 'preview_label' ) != null )
   {
      $( '#preview_label' ).append( '<a href="#" onclick="changeToHTML();$(this).remove();" >' + label + '</a>' );
   }

}

function changeToHTML()
{
   td = document.getElementById( 'preview' ).parentNode;
   td.children[1].src = document.getElementById( 'preview' ).value;

   var label = 'Switch to PDF';
   if ( typeof SUGAR.language !== 'undefined' ) {
      label = SUGAR.language.get( 'KTemplates', 'LBL_SWITCH_TO_PDF' );
   }
   if ( document.getElementById( 'preview_label_span' ) != null )
   {
      $( '#preview_label_span' ).append( '<a href="#" onclick="changeToPDF();$(this).remove();" >' + label + '</a>' );
   } else if ( document.getElementById( 'preview_label' ) != null )
   {
      $( '#preview_label' ).append( '<a href="#" onclick="changeToPDF();$(this).remove();" >' + label + '</a>' );
   }

}
