function getModuleName() {
   var module_name = '';
   if ( $( "#EditView input[name=module]" ).length > 0 ) {
      module_name = $( "#EditView input[name=module]" ).val();
   } else if ( $( "#formDetailView input[name=module]" ).length > 0 ) {
      module_name = $( "#formDetailView input[name=module]" ).val();
   } else if ( $( "input[name=module]" ).length > 0 ) {
      module_name = $( "input[name=module]" ).val();
   }
   return module_name;
}

function getSugarDateFormat() {
   var dtDefs = toSugarDate( (new Date), true );
   var format = [ ];
   format[dtDefs.yearPos] = '%Y';
   format[dtDefs.monthPos] = '%m';
   format[dtDefs.dayPos] = '%d';
   return format.join( dtDefs.separator );
}

function getLastItem( array ) {
   return array && array.length && array[array.length - 1];
}

function initToolbar() {
   var dtFormat = getSugarDateFormat();
   var form = getLastItem( document.querySelectorAll( 'form.TWSToolbarForm' ) );
   var input = form.querySelector( 'input.date_input' );
   var trigger = form.querySelector( 'img' );
   var randId = Math.floor( (1 + Math.random()) * 0x10000 ).toString( 16 ).substring( 1 );
   var formName = 'TWSDashletDateForm' + randId;
   var inputName = 'date_plan_' + randId;
   var triggerName = inputName + '_trigger';
   var firstDayOfWeek = $( '#first_day_of_week' );

   if ( firstDayOfWeek === null || firstDayOfWeek.val() < 0 ) {
      firstDayOfWeek.val( 1 );
   }

   form.setAttribute( 'name', formName );
   form.setAttribute( 'id', formName );
   input.setAttribute( 'name', inputName );
   input.setAttribute( 'id', inputName );
   trigger.setAttribute( 'id', triggerName );
   $( input ).keydown( function ( e ) {
      if ( (e.keyCode || e.which) == 13 ) {
         e.stopPropagation();
         e.preventDefault();
         return false;
      }
      return true;
   } );

   Calendar.setup( {
      inputField: inputName,
      form: formName,
      ifFormat: dtFormat,
      daFormat: dtFormat,
      button: triggerName,
      singleClick: true,
      dateStr: "",
      startWeekday: firstDayOfWeek.val(),
      step: 1,
      weekNumbers: false
   } );
}

$( document ).ready( function () {
   initToolbar();
} );