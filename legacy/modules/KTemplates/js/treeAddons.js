window.treeAddons = {
   insertToken: function ( text ) {
      var inst = tinyMCE.getInstanceById( "template" );
      if ( inst )
         inst.getWin().focus();
      inst.execCommand( 'mceInsertRawHTML', false, text );
   },
   insert_variable_html: function ( text ) {
      var myField = document.getElementById( 'template' );

      //IE support
      if ( document.selection ) {
         myField.focus();
         sel = document.selection.createRange();
         sel.text = myToken;
      }

      //Mozilla/Firefox/Netscape 7+ support
      else if ( myField.selectionStart || myField.selectionStart == '0' ) {

         var startPos = myField.selectionStart;
         var endPos = myField.selectionEnd;
         myField.value = myField.value.substring( 0, startPos ) + myToken + myField.value.substring( endPos, myField.value.length );

      } else {
         myField.value += myToken;
      }

   },
   insertRelBlock: function ( relationship ) {
      var ed = tinyMCE.activeEditor;
      var myField = document.getElementById( 'template' );
      var open = "<repeat type=\"link\" relationship=\"" + relationship + "\" intable=\"true\" table_outside=\"true\">" +
              "<table><thead><tr><th>Header</th><th>Header</th></tr></thead>" +
              "<tbody id=\"" + relationship + "\" relationship=\"" + relationship + "\">" +
              "<tr><td></td><td></td></tr>";
      var close = "</tbody></table></repeat>";

      treeAddons.insertToken( open + close );
   },
   insertMultienum: function ( multienumField ) {
      var myField = document.getElementById( 'template' );
      var mef = "\n<repeat type=\"multienum\" field=\"" + multienumField + "\">\n$ITEM\n</repeat>\n";
      treeAddons.insertToken( mef );
   }
}


