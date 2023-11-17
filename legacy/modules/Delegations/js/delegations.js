$( document ).ready( function () {
    disable_additional_information();
});

YAHOO.util.Event.onContentReady('EditView', function () {
   checkDelegationLocalCurrencyId();
   disable_currency();
   $( '#delegation_locale_name' ).blur( checkDelegationLocalCurrencyId );
} );

function popup_ret( popup_reply_data )
{
   from_popup_return = true;
   var form_name = popup_reply_data.form_name;
   var name_to_value_array = popup_reply_data.name_to_value_array;
   for ( var the_key in name_to_value_array )
   {
      if ( the_key != 'toJSON' )
      {
         var displayValue = name_to_value_array[the_key].replace( /&amp;/gi, '&' ).replace( /&lt;/gi, '<' ).replace( /&gt;/gi, '>' ).replace( /&#039;/gi, '\'' ).replace( /&quot;/gi, '"' );
         if ( window.document.forms[form_name] && window.document.forms[form_name].elements[the_key] )
         {
            window.document.forms[form_name].elements[the_key].value = displayValue;
            SUGAR.util.callOnChangeListers( window.document.forms[form_name].elements[the_key] );

         }
      }
   }
   CurrencyConvertAll( window.document.forms[form_name] );
   $( '#currency_text' ).html( $( "#currency_id_select option:selected" ).text() );
   checkDelegationLocalCurrencyId();
}

function disable_currency() {
   $( '#currency_id_select' ).hide();
   $( '#currency_id_span' ).html( "<span id='currency_text'>" + $( "#currency_id_select option:selected" ).text() + "</span>" + $( '#currency_id_span' ).html() );
}

QSCallbacksArray["EditView_delegation_locale_name"] = function ( sqs ) {
   sqs.group = 'and';
   sqs.conditions.push( {
      name: "archival",
      op: 'equal',
      end: '%',
      value: '\0',
   } );
};

function checkDelegationLocalCurrencyId() {
   var values = viewTools.form.getFormValues();
   if ( values.vt_delegation_locale_id ) {
      viewTools.api.callCustomApi( {
         module: 'Delegations',
         action: 'checkDelegationLocalCurrencyId',
         dataPOST: {
            delegation_locale_id: values.vt_delegation_locale_id
         },
         callback: function ( data ) {
            var selector = "#currency_id_select>option[value=";
            if ( data ) {
               viewTools.GUI.showField( $( '#exchange_rate' ) );
               viewTools.form.setValue( $( '#currency_id_select' ), data );
               var option = data + "]";

            } else {
               viewTools.GUI.hideField( $( '#exchange_rate' ) );
               viewTools.form.setValue( $( '#currency_id_select' ), -99 );
               var option = "-99]";
            }
            var full_selector = selector.concat( option );
            $( '#currency_text' ).text( $( full_selector ).text() );
            $( full_selector ).prop( 'selected', 'selected' );
         }
      } );
   } else {
      $( '#currency_id_select' ).hide();
      viewTools.GUI.hideField( $( '#exchange_rate' ) );
   }
}

function disable_additional_information() {
   var delegation_locale_id = $( 'span#delegation_locale_id' );
   if ( typeof (delegation_locale_id) !== 'undefined' && delegation_locale_id.data( 'id-value' ) ) {
      viewTools.api.callCustomApi( {
         module: 'Delegations',
         action: 'checkDelegationLocalCurrencyId',
         dataPOST: {
            delegation_locale_id: delegation_locale_id.data( 'id-value' )
         },
         callback: function ( data ) {
            if ( !data ) {
               $( '.panel-content' ).children().last().prev().hide();
            }
         }
      } );

   }
}
