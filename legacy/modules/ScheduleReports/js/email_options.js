window.email_options = {
   open_email_template_form: function ( fieldToSet ) {
      /*
       * Zmienna wykorzystywana globalnie powinna być zdefiniowana globalnie, np.: window.field_name = 1;
       */
      window.fieldToSetValue = fieldToSet;
      URL = "index.php?module=EmailTemplates&action=EditView&inboundEmail=true&show_js=1";
      windowName = 'email_template';
      windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

      win = window.open( URL, windowName, windowFeatures );
      if ( window.focus )
      {
         // put the focus on the popup if the browser supports the focus() method
         win.focus();
      }
   },
   edit_email_template_form: function ( templateField ) {
      window.fieldToSetValue = templateField;
      var field = document.getElementById( templateField );
      URL = "index.php?module=EmailTemplates&action=EditView&inboundEmail=true&show_js=1";
      if ( field.options[field.selectedIndex].value != 'undefined' ) {
         URL += "&record=" + field.options[field.selectedIndex].value;
      }
      windowName = 'email_template';
      windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

      win = window.open( URL, windowName, windowFeatures );
      if ( window.focus )
      {
         // put the focus on the popup if the browser supports the focus() method
         win.focus();
      }
   },
   refresh_email_template_list: function ( template_id, template_name ) {
      var field = document.getElementById( window.fieldToSetValue );
      //add item to selection list.
      if ( email_options.check_bfound( field, template_id, template_name ) == 0 ) {
         email_options.addItemToSelection( field, template_id, template_name );
      }

      //enable the edit button.
      var editButtonName = 'edit_email_template_id';

      var field1 = document.getElementById( editButtonName );
      field1.style.visibility = "visible";

      var field2 = document.getElementById( 'email_template_id' );
      if ( email_options.check_bfound( field2, template_id, template_name ) == 0 ) {
         email_options.addItemToSelection( field2, template_id, template_name );
      }

   },
   check_bfound: function ( field, template_id, template_name ) {
      var bfound = 0;
      for ( var i = 0; i < field.options.length; i++ ) {
         if ( field.options[i].value == template_id ) {
            if ( field.options[i].selected == false ) {
               field.options[i].selected = true;
            }
            field.options[i].text = template_name;
            bfound = 1;
         }
      }
      return bfound;
   },
   addItemToSelection: function ( field, template_id, template_name ) {
      var newElement = document.createElement( 'option' );
      newElement.text = template_name;
      newElement.value = template_id;
      field.options.add( newElement );
      newElement.selected = true;
   }
}