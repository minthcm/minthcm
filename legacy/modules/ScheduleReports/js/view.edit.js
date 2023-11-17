
function updateTemplatesList( popup_data ) {
   if ( popup_data ) {
      set_return( popup_data );
   }

   var kreport_id = $( "#kreport_id" ).val();
   var template_id_select = $( "#template_id" )[0];
   var old_template_id = $( "#template_id_oldvalue" ).val();

   if ( kreport_id ) {
      var response_data;
      var request_data = {
         module: "KTemplates",
         action: "getReportPDFTemplates",
         sugar_body_only: 1,
         kreport_id: kreport_id
      };

      $.ajax( {
         method: "GET",
         data: request_data,
         dataType: "json",
         error: function ( jq_XHR, text_status, error_thrown ) {
            console.log( "Loading of templates list failed, error from server: " + error_thrown );
         },
         success: function ( data, text_status, jq_XHR ) {
            response_data = data;
         },
         complete: function ( jq_XHR, text_status ) {
            $( "#template_id option" ).remove();

            if ( text_status == "success" && response_data ) {
               for ( var array_key = 0; array_key < response_data.length; array_key++ ) {
                  if ( response_data[array_key].id == old_template_id ) {
                     template_id_select.append( new Option( response_data[array_key].name, response_data[array_key].id, true, true ) );
                  } else {
                     template_id_select.append( new Option( response_data[array_key].name, response_data[array_key].id ) );
                  }
               }
            }
         }
      } );
   }
}