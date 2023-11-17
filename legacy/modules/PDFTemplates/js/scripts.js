var temp_template_id = null;
function start() {
   if(SUGAR.App){}else{
    document.getElementById('relatedmodule').parentNode.parentNode.style.display = 'none';
 }
}
//window.onload = start;
$(document).ready(function ()
{
  start()
});
setTimeout(function () {
    tinyMCE.init({
        'schema': 'html5',
        "convert_urls": false,
        "entity_encoding": 'raw',
        "height": 600,
        "width": "100%",
        "theme": "advanced",
        "theme_advanced_resizing": true,
        "theme_advanced_resizing_max_height": 650,
        "theme_advanced_resizing_min_height": 650,
        "theme_advanced_toolbar_align": "left",
        "theme_advanced_toolbar_location": "top",
        "theme_advanced_buttons1": "addPDFHeader,addPDFFooter,addCurrentTime,code,help,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,forecolor,backcolor,separator,styleselect,formatselect,fontselect,fontsizeselect,",
        "theme_advanced_buttons2": "cut,copy,paste,pastetext,pasteword,selectall,separator,search,replace,separator,bullist,numlist,separator,outdent,indent,separator,ltr,rtl,separator,undo,redo,separator,link,unlink,anchor,image,separator,sub,sup,separator,charmap,visualaid",
        "theme_advanced_buttons3": "tablecontrols,separator,advhr,hr,removeformat,separator,insertdate,inserttime,separator,preview,styleprops,OpenPDFpreview,CheckTemplateSyntax,|,insertlayer,moveforward,movebackward,absolute",
        "strict_loading_mode": true,
        "mode": "textareas",
        "language": "en",
        "plugins": "advhr,insertdatetime,table,preview,paste,directionality,style,noneditable,layer,visualblocks,inlinepopups",
        'visualblocks_default_state': true,
        // "elements":"style", - works only with exact mode
        "custom_elements": "pdf_footer, pdf_header, pdf_preview,repeat",
        "extended_valid_elements": "div[id|style|height|pdf_footer|pdf_header|type|relationship|intable|border|width|class],repeat[id|style|height|field|pdf_footer|pdf_header|type|relationship|intable|table_outside|border|width|class],style,hr[class|width|size|noshade],tbody[id|relationship],@[class|style],pdf_footer,pdf_header," +
        // for SugarCRM 7.x.x
        	"tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],thead,tbody[id|relationship],#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],#th[colspan|rowspan|width|height|align|valign|scope]"
            +",table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor]",
        //end
        "content_css": "include\/javascript\/tiny_mce\/themes\/advanced\/skins\/default\/content.css",
        template_external_list_url: "js/template_list.js",
        external_link_list_url: "js/link_list.js",
        external_image_list_url: "js/image_list.js",
        media_external_list_url: "js/media_list.js",
        //"style_formats":[{title : 'repeat', block : 'repeat', wrapper: true, merge_siblings: false}],
        setup: function (ed) {

            // Add a custom button
            ed.addButton('addPDFHeader', {
                title: SUGAR.language.get('PDFTemplates', 'LBL_ADDHEADER_PDF'),
                image: 'modules/PDFTemplates/tiny/pdf_header.gif',
                onclick: function () {
                    // Add you own code to execute something on click
                    ed.focus();
                    ed.selection.setContent('<div id="pdf_header">INSERT HEADER HERE</div>');
                }
            }
            );
            ed.addButton('addPDFFooter', {
                title: SUGAR.language.get('PDFTemplates', 'LBL_ADDFOOTER_PDF'),
                image: 'modules/PDFTemplates/tiny/pdf_footer.gif',
                onclick: function () {
                    // Add you own code to execute something on click
                    ed.focus();
                    ed.selection.setContent('<div id="pdf_footer">INSERT FOOTER HERE</div>');
                }
            });
            ed.addButton('addCurrentTime', {
                title: SUGAR.language.get('PDFTemplates', 'LBL_ADD_CURRENT_DATE'),
                image: 'modules/PDFTemplates/tiny/pdf_calendar.gif',
                onclick: function () {
                    // Add you own code to execute something on click
                    ed.focus();
                    ed.selection.setContent('$_CURRENT_DATE');
                }
            });

            ed.addButton('OpenPDFpreview', {
                title: SUGAR.language.get('PDFTemplates', 'LBL_PDF_PREVIEW'),
                image: 'modules/PDFTemplates/tiny/pdf_preview.gif',
                onclick: function () {
                    // The name of the module for which it is designed template needed to generate preview
                    related_m = document.getElementById('relatedmodule');
                    url_string_add = '';
                    // We check to see if it is the first to generate a preview, if you do not add to the link saved id
                    if (temp_template_id != null)
                    {
                        url_string_add = '&save_to_id=' + temp_template_id;
                    }
                    // Message html to write for previewing (returns the id of the temporary template)
                    $.post('index.php?module=PDFTemplates&action=saveTempTemplate&query=true&sugar_body_only=1' + url_string_add, {html_data: ed.getContent()}, function (result) {
                        var obj = jQuery.parseJSON(result);

                        winW = window.innerWidth;
                        winH = window.innerHeight;
                        // It adds to the div obscuring the normal elements
                        $("<div id='iframe_preview' style='position:fixed;background-color:#BFBBBD;z-index:10;top:"+$('nav').css('height')+";left:0px;width:100%;height:100%;'></div>").appendTo('body');
                        // Adds to this div floating frame with a url for viewing PDF file
                        $('#iframe_preview').html("<div style='width:100%; height:40px;background-color:#3B5998;' align='center'><a onclick='$(\"#iframe_preview\").remove();' style='color:white;font-size:x-large;font-weight:bolder;line-height: 1.5;'>"+SUGAR.language.get('PDFTemplates', 'LBL_CLOSE_PREVIEW')+"</a></div><iframe width='100%' height='" + (winH - 40) + "px' src='index.php?module=PDFGenerator&action=Preview&module_name=" + related_m.value + "&query=true&sugar_body_only=1&temp_template=true&template=" + obj.template_id + "'><iframe>");

                        // Version with opening windows
                        //WinId = window.open('index.php?module=PDFGenerator&action=Preview&module_name='+ related_m.value +'&query=true&sugar_body_only=1&temp_template=true&template='+obj.template_id,'PDF PREVIEW');

                        // rewriting id
                        temp_template_id = obj.template_id;
                    });
                }
            });

            ed.addButton('CheckTemplateSyntax', {
                title: SUGAR.language.get('PDFTemplates', 'LBL_CHECK_SYNTAX'),
                image: 'modules/PDFTemplates/tiny/pdf_checksyntax.gif',
                onclick: function () {
                    // The name of the module for which it is designed template needed to generate preview
                    var related_m = document.getElementById('relatedmodule');
                    url_string_add = '';

                    //  Message html to write for previewing (returns the id of the temporary template)
                    $.post('index.php?module=PDFTemplates&action=checkSyntax&query=true&sugar_body_only=1&for_module=' + related_m.value, {page_content: ed.getContent()}, function (result) {
                        var obj = jQuery.parseJSON(result);
                        var message = '';
                        if (obj.error == 0)
                        {
                            message = obj.message;
                        }
                        else
                        {

                            message = obj.message + "\n";

                            for (variable in obj.errors)
                            {
                                message = message + obj.errors[variable] + "\n";
                            }
                        }
                        alert(message);

                    });
                }
            });



        }}
    );
}, 1000);
function insertToken(text) {
//function insert_variable_html(text){
    var inst = tinyMCE.getInstanceById("template");
    if (inst)
        inst.getWin().focus();
    inst.execCommand('mceInsertRawHTML', false, text);
}
//function insertToken(myToken) {
function insert_variable_html(text) {
    var myField = document.getElementById('template');

    //IE support 
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myToken;
    }

    //Mozilla/Firefox/Netscape 7+ support 
    else if (myField.selectionStart || myField.selectionStart == '0') {

        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos) + myToken + myField.value.substring(endPos, myField.value.length);

    } else {
        myField.value += myToken;
    }
}

function insertRelBlock(relationship) {
    var ed = tinyMCE.activeEditor;
    var myField = document.getElementById('template');
    var open = "<repeat type=\"link\" relationship=\"" + relationship + "\" intable=\"true\" table_outside=\"true\" >" +
            "<table><thead><tr><th>Header</th><th>Header</th></tr></thead>" +
            "<tbody id=\"" + relationship + "\" relationship=\"" + relationship + "\">" +
            "<tr><td></td><td></td></tr>";
    var close = "</tbody></table></repeat>";

    insertToken(open + close);
}
function insertMultienum(multienumField) {
    var myField = document.getElementById('template');
    var mef = "\n<repeat type=\"multienum\" field=\"" + multienumField + "\">\n$ITEM\n</repeat>\n";
    insertToken(mef);
}