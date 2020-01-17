$( document ).ready( function () {
   var OnboardingOffboardingElementsViewEdit = function () {

      var _form_selector = $( 'form#EditView' );

      this.construct = function () {
         run();
      };

      var run = function () {
         beforeSave();
         setTypeList();
      };

      var beforeSave = function () {
         viewTools.form.beforeSave( function () {
            var result = validateTaskDuration();
            if ( result === false ) {
               viewTools.form.focusOnFirstError();
            }
            return result;
         } );
      };

      var validateTaskDuration = function () {
         var result = true;
         var task_duration_hours = _form_selector.find( '#task_duration_hours' );
         var task_duration_minutes = _form_selector.find( '#task_duration_minutes' );
         var task_duration_hours_val = parseInt( task_duration_hours.val() );
         var task_duration_minutes_val = parseInt( task_duration_minutes.val() );
         if ( task_duration_hours_val < 0 || task_duration_minutes_val < 0 ) {
            result = false;
            viewTools.GUI.fieldErrorMark( task_duration_hours, viewTools.language.get( 'OnboardingOffboardingElements', 'LBL_ERR_NOTICE_DURATION_TIME' ) );
         }
         return result;
      };

      var setTypeList = function () {
         var element_id = _form_selector.find( 'input[type="hidden"][name="record"]' ).val();
         var relate_to = _form_selector.find( 'input[type="hidden"][name="relate_to"]' ).val();
         var element_type_selector = _form_selector.find( '#type option[value=exit_interview]' );
         if ( typeof element_id !== 'undefined' && !_.isEmpty( element_id ) ) {
            viewTools.api.callCustomApi( {
               module: 'OnboardingOffboardingElements',
               action: 'isRelatedToOnboardingTemplates',
               dataPOST: {
                  element_id: element_id
               },
               callback: function ( data ) {
                  if ( data ) {
                     element_type_selector.css( 'display', 'none' );
                  }
               }
            } );
         } else if ( typeof relate_to !== 'undefined' && relate_to === 'onboardingoffboardingelements_onboardingtemplates' ) {
            element_type_selector.css( 'display', 'none' );
         }
      };

      this.construct();
   };

   if ( typeof onboarding_offboarding_elements_view_edit === 'undefined' ) {
      onboarding_offboarding_elements_view_edit = new OnboardingOffboardingElementsViewEdit();
   }
} );
