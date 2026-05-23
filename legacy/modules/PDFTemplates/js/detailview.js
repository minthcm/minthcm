function changeToPDF()
{
    var switchtoHTML = SUGAR.language.get('PDFTemplates', 'LBL_SWITCH_TO_HTML');
    var m_name = document.getElementById('relatedmodule').value;
    var template_id = document.getElementsByName('record')[0].value;
    document.getElementById('iframe_preview').src = "index.php?module=PDFGenerator&action=Preview&query=true&sugar_body_only=1&template=" + template_id + "&module_name=" + m_name;

    if (document.getElementById('preview_label_span') != null)
    {
        $('#preview_label_span').append('<a href="#" onclick="changeToHTML();$(this).remove();" >' + switchtoHTML + '</a>');
    } else if (document.getElementById('preview_label') != null)
    {
        $('#preview_label').append('<a href="#" onclick="changeToHTML();$(this).remove();" >' + switchtoHTML + '</a>');
    }

}

function changeToHTML()
{
    var switchtoPDF = SUGAR.language.get('PDFTemplates', 'LBL_SWITCH_TO_PDF');
    var str = document.getElementById('preview').value;
    var str2 = str.replace(/http:\/\//g, '');
    var str2 = str2.replace(/https:\/\//g, '');
    var str3 = "https://" + str2;
    document.getElementById('iframe_preview').src = str3;
    if (document.getElementById('preview_label_span') != null)
    {
        $('#preview_label_span').append('<a href="#" onclick="changeToPDF();$(this).remove();" >' + switchtoPDF + '</a>');
    } else if (document.getElementById('preview_label') != null)
    {
        $('#preview_label').append('<a href="#" onclick="changeToPDF();$(this).remove();" >' + switchtoPDF + '</a>');
    }

}

function firstToPDF() {
    var switchtoPDF = SUGAR.language.get('PDFTemplates', 'LBL_SWITCH_TO_PDF');
    if (switchtoPDF == 'undefined') {
        switchtoPDF = '';
    }
    if (document.getElementById('preview_label_span') != null)
    {
        $('#preview_label_span').append('<BR><a href="#" onclick="changeToPDF();$(this).remove();" >' + switchtoPDF + '</a>');

    } else if (document.getElementById('preview_label') != null)
    {
        $('#preview_label').append('<BR><a href="#" onclick="changeToPDF();$(this).remove();" >' + switchtoPDF + '</a>');

    }
    var preview_label_cell = document.getElementById('preview_label_span').parentNode;
    preview_label_cell.width = '4%';
    if (switchtoPDF == 'undefined' || switchtoPDF == '') {
        setTimeout(function () {
            switchtoPDF = SUGAR.language.get('PDFTemplates', 'LBL_SWITCH_TO_PDF');
            if (document.getElementById('preview_label_span') != null)
            {
                $('#preview_label_span a').html(switchtoPDF);
            } else if (document.getElementById('preview_label') != null)
            {
                $('#preview_label a').html(switchtoPDF);
            }
        }, 1000);
    }
}
$(document).ready(function ()
{
    firstToPDF();
    var str = document.getElementById('preview').value;
    var str2 = str.replace(/http:\/\//g, '');
    var str2 = str2.replace(/https:\/\//g, '');
    var str3 = "https://" + str2;
    document.getElementById('iframe_preview').src = str3;


});


