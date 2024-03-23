function assignRepeatDow() {
   document.getElementById( 'repeat_dow' ).value = Array.prototype.slice.call( document.querySelectorAll( 'input[id^=repeat_dow_]' ) ).filter( function ( e ) {
      return e.checked;
   } ).map( function ( e ) {
      return /(\d)$/.exec( e.id )[1];
   } ).join( '' );
}

function toggle_repeat_type() {

   if ( document.forms['EditView'].repeat_type.value == "" ) {
      document.getElementById( "repeat_interval_row" ).style.display = "none";
      document.getElementById( "repeat_end_row" ).style.display = "none";
   } else {
      document.getElementById( "repeat_interval_row" ).style.display = "";
      document.getElementById( "repeat_end_row" ).style.display = "";
      toggle_repeat_end();
   }

   var repeat_dow_row = document.getElementById( "repeat_dow_row" );
   if ( document.forms['EditView'].repeat_type.value == "Weekly" ) {
      repeat_dow_row.style.display = "";
   } else {
      repeat_dow_row.style.display = "none";
   }

   var intervalTextElm = document.getElementById( 'repeat-interval-text' );
   if ( intervalTextElm && typeof app_list_strings_repeat_intervals != 'undefined' ) {
      intervalTextElm.innerHTML = app_list_strings_repeat_intervals[document.forms['EditView'].repeat_type.value];
   }
}

function toggle_repeat_end() {
   if ( document.getElementById( "repeat_count_radio" ).checked ) {
      document.forms['EditView'].repeat_until.setAttribute( "disabled", "disabled" );
      document.forms['EditView'].repeat_count.removeAttribute( "disabled" );
      document.getElementById( "repeat_until_trigger" ).style.display = "none";

      if ( typeof validate != "undefined" && typeof validate['EditView'] != "undefined" ) {
         removeFromValidate( 'EditView', 'repeat_until' );
      }
      addToValidateMoreThan( 'EditView', 'repeat_count', 'int', true, '{/literal}{$MOD.LBL_REPEAT_COUNT}{literal}', 1 );
   } else {
      document.forms['EditView'].repeat_count.setAttribute( "disabled", "disabled" );
      document.forms['EditView'].repeat_until.removeAttribute( "disabled" );
      document.getElementById( "repeat_until_trigger" ).style.display = "";

      if ( typeof validate != "undefined" && typeof validate['EditView'] != "undefined" ) {
         removeFromValidate( 'EditView', 'repeat_count' );
      }
      addToValidate( 'EditView', 'repeat_until', 'date', true, '{/literal}{$MOD.LBL_REPEAT_UNTIL}{literal}' );
   }

   // prevent an issue when a calendar date picker is hidden under a dialog
   var editContainer = document.getElementById( 'cal-edit_c' );
   if ( editContainer ) {
      var pickerContainer = document.getElementById( 'container_repeat_until_trigger_c' );
      if ( pickerContainer ) {
         pickerContainer.style.zIndex = editContainer.style.zIndex + 1;
      }
   }
}

var repeatTypeSelect = document.querySelector( 'select[name=repeat_type]' );
repeatTypeSelect.dispatchEvent( new Event( 'change' ) );
document.getElementById( 'repeat_dow' ).value.split( '' ).forEach( function ( e ) {
   var f = document.getElementById( 'repeat_dow_' + e );
   f && (f.checked = true);
} );