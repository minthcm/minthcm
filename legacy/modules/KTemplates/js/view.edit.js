var temp_template_id = null;

$( document ).ready( function () {
   initTinymce( 100 );
} );

/*
 * Init tinymce
 */
function initTinymce( retrieve_limit ) {
   if ( retrieve_limit == null || retrieve_limit == undefined ) {
      retrieve_limit = 1;
   }
   setTimeout( function () {
      if ( tinymce !== undefined ) {
         tinymce.init( {
            'schema': 'html5',
            "convert_urls": false,
            "height": 600,
            "width": "100%",
            "theme": "advanced",
            "theme_advanced_resizing": true,
            "theme_advanced_resizing_max_height": 650,
            "theme_advanced_resizing_min_height": 650,
            "theme_advanced_toolbar_align": "left",
            "theme_advanced_toolbar_location": "top",
            "theme_advanced_buttons1": "addPDFHeader,addPDFFooter,code,help,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,forecolor,backcolor,separator,styleselect,formatselect,fontselect,fontsizeselect,",
            "theme_advanced_buttons2": "cut,copy,paste,pastetext,pasteword,selectall,separator,search,replace,separator,bullist,numlist,separator,outdent,indent,separator,ltr,rtl,separator,undo,redo,separator,link,unlink,anchor,image,separator,sub,sup,separator,charmap,visualaid",
            "theme_advanced_buttons3": "tablecontrols,separator,advhr,hr,removeformat,separator,insertdate,inserttime,separator,preview,styleprops,OpenPDFpreview,CheckTemplateSyntax,|,insertlayer,moveforward,movebackward,absolute",
            "strict_loading_mode": true,
            "mode": "textareas",
            "language": "en",
            "plugins": "advhr,insertdatetime,table,preview,paste,directionality,style,noneditable,layer,visualblocks,inlinepopups",
            'visualblocks_default_state': true,
            // "elements":"style", - works only with exact mode
            "custom_elements": "pdf_footer, pdf_header, pdf_preview,repeat",
            "extended_valid_elements": "div[id|style|height|pdf_footer|pdf_header|type|relationship|intable|border|width|class],repeat[id|style|height|pdf_footer|pdf_header|type|relationship|intable|table_outside|border|width|class],style,hr[class|width|size|noshade],tbody[id|relationship],@[class|style],pdf_footer,pdf_header",
            "content_css": "include\/javascript\/tiny_mce\/themes\/advanced\/skins\/default\/content.css",
            template_external_list_url: "js/template_list.js",
            external_link_list_url: "js/link_list.js",
            external_image_list_url: "js/image_list.js",
            media_external_list_url: "js/media_list.js",
            //"style_formats":[{title : 'repeat', block : 'repeat', wrapper: true, merge_siblings: false}],
            setup: function ( ed ) {
               // Add a custom button
               ed.addButton( 'addPDFHeader', {
                  title: 'Dodaj header PDF',
                  image: 'images/tiny/pdf_header.gif',
                  onclick: function () {
                     // Add you own code to execute something on click
                     ed.focus();
                     ed.selection.setContent( '<div id="pdf_header">INSERT HEADER HERE</div>' );
                  }
               }
               );
               ed.addButton( 'addPDFFooter', {
                  title: 'Dodaj footer PDF',
                  image: 'images/tiny/pdf_footer.gif',
                  onclick: function () {
                     // Add you own code to execute something on click
                     ed.focus();
                     ed.selection.setContent( '<div id="pdf_footer">INSERT FOOTER HERE</div>' );
                  }
               } );

               ed.addButton( 'OpenPDFpreview', {
                  title: 'Otwórz podgląd PDF',
                  image: 'images/tiny/pdf_preview.gif',
                  onclick: function () {
                     
                     related_m = document.getElementById( 'relatedmodule' );
                     url_string_add = '';
                     
                     if ( temp_template_id != null )
                     {
                        url_string_add = '&save_to_id=' + temp_template_id;
                     }
                     
                     $.post( 'index.php?module=KTemplates&action=save_temp_template&query=true&sugar_body_only=1' + url_string_add, {html_data: ed.getContent()}, function ( result ) {
                        var obj = jQuery.parseJSON( result );

                        winW = window.innerWidth;
                        winH = window.innerHeight;
                        
                        $( "<div id='iframe_preview' style='position:fixed;background-color:#BFBBBD;z-index:10;top:0px;left:0px;width:100%;height:100%;'></div>" ).appendTo( 'body' );
                        
                        $( '#iframe_preview' ).html( "<div style='width:100%; height:40px;background-color:#3B5998;' align='center'><a onclick='$(\"#iframe_preview\").remove();' style='color:white;font-size:x-large;font-weight:bolder;line-height: 1.5;'>Zamknij podgląd</a></div><iframe width='100%' height='" + (winH - 40) + "px' src='index.php?module=PDFGenerator&action=Preview&module_name=" + related_m.value + "&query=true&sugar_body_only=1&temp_template=true&template=" + obj.template_id + "'><iframe>" );

                        
                        //WinId = window.open('index.php?module=PDFGenerator&action=Preview&module_name='+ related_m.value +'&query=true&sugar_body_only=1&temp_template=true&template='+obj.template_id,'PDF PREVIEW');

                        
                        temp_template_id = obj.template_id;
                     } );
                  }
               } );

               ed.addButton( 'CheckTemplateSyntax', {
                  title: 'Check Syntax',
                  image: 'images/tiny/pdf_checksyntax.gif',
                  onclick: function () {
                     
                     var related_m = document.getElementById( 'relatedmodule' );
                     url_string_add = '';

                     
                     $.post( 'index.php?module=KTemplates&action=checkSyntax&query=true&sugar_body_only=1&for_module=' + related_m.value, {page_content: ed.getContent()}, function ( result ) {
                        var obj = jQuery.parseJSON( result );
                        var message = '';
                        if ( obj.error == 0 )
                        {
                           message = obj.message;
                        } else
                        {

                           message = obj.message + "\n";

                           for ( variable in obj.errors )
                           {
                              message = message + obj.errors[variable] + "\n";
                           }
                        }
                        alert( message );

                     } );
                  }
               } );
            }}
         );
      } else if ( retrieve_limit > 0 ) {
         initTinymce( retrieve_limit - 1 );
      }
   }, 100 );
}