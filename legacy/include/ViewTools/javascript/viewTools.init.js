var viewTools = window.viewTools;
viewTools.form._save_button_selector = 'form input[id^=SAVE],form input[title="Zapisz"][class="button"],form input[title="Save"][class="button"],form input[id^="save"],form button[id^="save"],form div.buttons>input[type="button"][id="CLOSE_CREATE_HEADER"], .popupBody form[name^="form_QuickCreate"] div.buttons input[id$="save_button"]';
viewTools.form.error_count = 0;
viewTools.form.validation_state = 0; // 0 - waiting, 1 - prepared, 2 - validating
viewTools.form.throttle_save = false
viewTools.form.prepareViewToolsValidation = function () {
   if ( viewTools.form.validation_state != 0 ) {
      return false;
   }

   if (viewTools.form.throttle_save) {
      return false;
   } 
   viewTools.form.throttle_save = true;

   viewTools.form.disableSaveButton();
   viewTools.form.validation_state = 1;
   viewTools.GUI.fieldErrorUnmark();
   setTimeout( viewTools.form.startViewToolsValidation.call( this ), 20 );
};
viewTools.form.startViewToolsValidation = function () {
   if ( viewTools.form.validation_state != 1 ) {
      viewTools.form.throttle_save = false;
      return false;
   }
   viewTools.form.validation_state = 2;
   var form = $( this ).closest( 'form' );
   viewTools.form.error_count = 0;
   if ( viewTools.cache.requiredsToSetBeforeSave.length > 0 ) {
      $( viewTools.cache.requiredsToSetBeforeSave ).each( function () {
         if ( typeof this.isRequired != "undefined" ) {
            viewTools.form.setFieldRequirement( this.handler, this.isRequired );
         } else {
            viewTools.form.fieldChangeEvent( this.handler );
         }
      } );
      viewTools.cache.requiredsToSetBeforeSave = [ ];
   }
   //Defined enforced actions before save
   var form_beforeSave = true;
   let form_name = form.attr( 'name' );
   for ( key in viewTools.cache.form_beforeSave_enforced ) {
      var tmp_function = viewTools.cache.form_beforeSave_enforced[key];
      if ( tmp_function(form_name) === false ) {
         form_beforeSave = false;
         viewTools.form.error_count++;
      }
   }
   //Custom validations
   var form_validation = viewTools.cache.form_save( form );
   //check suite validations
   var default_validation = check_form( form.attr( 'name' ), '' );
   if ( form_validation === true && default_validation === true ) {
      //Defined unenforced actions
      for ( key in viewTools.cache.form_beforeSave ) {
         if ( form_beforeSave === true ) {
            var tmp_function = viewTools.cache.form_beforeSave[key];
            if ( tmp_function(form_name) === false ) {
               form_beforeSave = false;
               viewTools.form.error_count++;
            }
         }
      }
      if ( form_beforeSave === false ) {
         //check suite validations and display errors
         check_form( form.attr( 'name' ), '' );
         viewTools.form.focusOnFirstError();
         viewTools.form.onValidationEnd();
         return false;
      }
      //Defined actions after save
      var form_afterSave = true;
      for ( var key in viewTools.cache.form_afterSave ) {
         if ( form_afterSave === true ) {
            var tmp_function = viewTools.cache.form_afterSave[key];
            if ( tmp_function(form_name) === false ) {
               form_afterSave = false;
               viewTools.form.onValidationEnd();
               return false;
            }
         }
      }
      var tmp_validation_function = viewTools.cache.save_action[form.attr( 'id' )][this.id];
      if ( tmp_validation_function === undefined ) { //viewTools tech note "some forms does not have any action on save button, so system return undefined. In this case system has to replace var as string"
         tmp_validation_function = '';
      }
      tmp_validation_function = tmp_validation_function.replace( "(this.form) ? this.form : document.forms[0]", '$("#' + $( this ).closest( 'form' ).attr( 'id' ) + '")[0]' );
      try {
         eval( 'function tmp_form_validate(){ lastSubmitTime = 0;' + tmp_validation_function + ';}' );
         var result_of_validation = tmp_form_validate.call( this );
      } catch ( e ) {
         console.log( 'vt_error[viewTools.init.js#103]: ' + e );
      }
      var onsubmit = form.onsubmit;
      if ( result_of_validation != false && this.type == "submit" && (typeof onsubmit !== "function" || onsubmit()) ) {
         form.submit();
      }
      viewTools.form.onValidationEnd();
      return false;
   } else {
      viewTools.form.focusOnFirstError();
      viewTools.form.onValidationEnd();
      return false;
   }
};
viewTools.form.onValidationEnd = function () {
   if ( viewTools.form.error_count > 0 ) {
      viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_FORM_WITH_ERRORS' ), 'error', 6000 );
   } else {
      viewTools.GUI.statusBox.showStatus( viewTools.language.get('app_strings', 'LBL_SAVING') + '...', 'success');
   }
   setTimeout( function () {
      viewTools.form.validation_state = 0;
      viewTools.form.enableSaveButton();
      viewTools.form.throttle_save = false;
   }, 20 );
};
viewTools.form.calculateSelectors = function () {
   if ( $( '.vt_formulaSelector' ).length > 0 ) {
      $( '.vt_formulaSelector' ).each( function () {
         //Init field
         if ( $( this ).data( 'modulename' ) === undefined && $( this ).hasClass( 'vt_formulaSelector' ) ) {
            viewTools.form.blur( $( this ), true );
         }
      } );
   }
};
viewTools.form.disableSaveButton = function () {
    var save_button_handler = $( viewTools.form._save_button_selector );
    save_button_handler.prop('disabled', true);
    save_button_handler.css({ 'height': '32px', 'background-color': '#888' });
}
viewTools.form.enableSaveButton = function () {
   var save_button_handler = $( viewTools.form._save_button_selector );
   save_button_handler.css({ 'height': '', 'background-color': '' });
   save_button_handler.prop('disabled', false);
}
if ( window.disable_vt_tools === undefined || window.disable_vt_tools === false ) {
   $( document ).on( 'blur', '.vt_formulaSelector', function () {
      viewTools.form.fieldChangeEvent( this );
   } );
   $( document ).on( 'change', 'select.vt_formulaSelector', function () {
      viewTools.form.fieldChangeEvent( this );
   } );
   /*
    * Event inited on every creation of  vt_formulaSelector fields
    */
   $( document ).on( 'DOMNodeInserted', function ( ) {
      //Init save events
      if ( viewTools.cache.form_save !== undefined ) {
         var disabled_buttons = [ 'saved_search_submit' ];
         var disabled_buttons_id = [ 'wiz_submit_button','wiz_submit_button_perm' ];
         /*
          * Init form actions
          * - partly moved from formValidation
          * - goal is to move all formValidation to below convention
          */
         var save_button_handler = $( viewTools.form._save_button_selector );
         save_button_handler.each( function () {
            if ( !$( this ).hasClass( 'vt_submit' ) && $.inArray( this.name, disabled_buttons ) < 0 &&  $.inArray( this.id, disabled_buttons_id ) < 0 ) {
               $( this ).addClass( 'vt_submit' );
               //Select form
               var form = $( this ).closest( 'form' ).first();
               if ( !form.hasClass( 'vt_form' ) ) {
                  form.addClass( 'vt_form' );
               }
               //store old onclick action on cache
               if ( viewTools.cache.save_action[form.attr( 'id' )] === undefined ) {
                  viewTools.cache.save_action[form.attr( 'id' )] = {};
               }
               viewTools.cache.save_action[form.attr( 'id' )][this.id] = $( this ).attr( 'onclick' );
               /*
                * Set new onclick action
                */
               $( this ).removeAttr( 'onclick' );
               $( this ).on( 'click', function () {
                  viewTools.form.prepareViewToolsValidation.call( this );
                  return false;
               } );
            }
         } );
      }

      //Execute onAppear event
      for ( key in viewTools.cache.onAppear ) {
         var onAppear = viewTools.cache.onAppear[key];
         if ( $( onAppear.handler ).length > 0 ) {
            onAppear.callback();
            delete viewTools.cache.onAppear[key];
         }
      }
   } );
   /*
    * Back to edit form
    */
   $( document ).on( 'click', 'button.vt_duplicate_btnback', function () {
      var editform = $( '#vt_duplicate_message' ).parent().find( 'form' ).parent();
      if ( $( '#vt_duplicate_message' ).parent().find( 'form' ).closest( 'form' ).hasClass( 'vt_CalendarEditView' ) ) {
         editform = $( '#vt_duplicate_message' ).parent().find( 'form' ).parent().parent();
         $( 'div#cal-edit-buttons' ).fadeIn( 300 );
      }
      $( '#vt_duplicate_message' ).fadeOut( 300, function () {
         editform.fadeIn( 300 );
      } );
   } );
   /*
    * Ignore duplicate warning and save
    */
   $( document ).on( 'click', 'button.vt_duplicate_btnignore', function () {
      //ignore duplicates
      viewTools.cache.ignoreDuplicates = true;
      //Submit form
      var form = $( '.vt_formulaSelector' ).last().closest( 'form' );

      //EditView
      if ( form.hasClass( 'EditView' ) || form.attr( 'id' ) == 'EditView' ) {
         //$( 'input[id=SAVE_HEADER]' ).last().trigger( 'click' );
         $( 'input[id=SAVE]' ).last().trigger( 'click' );
      }
      //Calendar Edit View
      else if ( form.hasClass( 'CalendarEditView' ) ) {
         $( 'button[id="btn-save"]' ).last().trigger( 'click' );
      }
      //subpanel form
      else {
         form.find( 'div.action_buttons' ).first().find( 'input.button' ).first().trigger( 'click' );
      }
      //Display form
      $( 'button.vt_duplicate_btnback' ).trigger( 'click' );
   } );
   /**
    * Set event for form validation
    */
   viewTools.form.save( function ( ) {
      var ret = true;
      //Set form vt_fields for validation
      var formFields = null;
      if ( arguments[0] !== undefined ) {
         formFields = arguments[0].find( '.vt_formulaSelector' );
      } else {
         formFields = $( '.vt_formulaSelector' );
      }
      formFields.each( function () {
         window.viewTools.cache.AEM = [ ];
         /*
          * Check if validation is set
          */
         if ( $( this ).data( 'validation' ) !== undefined ) {
            //if it is lead conversion, and field is not visible system does not should validate it
            if ( !($( '#ConvertLead' ).length > 0 && $( this ).closest( 'div[id^="create"]:hidden' ).length > 0) ) {
               var validation_array = $( this ).data( 'validation' ).split( ';' );
               for ( var validation_idx in validation_array ) {
                  formula = viewTools.formula._parseFormula( validation_array[validation_idx], $( this ), 'validation', validation_idx );
                  if ( eval( formula ) == false ) {
                     ret = false;
                     viewTools.form.error_count++;
                     if ( window.viewTools.cache.AEM.length > 0 ) {
                        for ( key in window.viewTools.cache.AEM ) {
                           window.viewTools.GUI.fieldErrorMark( $( this ), window.viewTools.cache.AEM[key] );
                        }
                        window.viewTools.cache.AEM = [ ];
                     } else {
                        viewTools.GUI.fieldErrorFormulaMark( $( this ), 'validation' );
                     }
                  }
               }
            }
         }
         if ( $( this ).data( 'required' ) !== undefined ) {
            formula = viewTools.formula._parseFormula( $( this ).data( 'required' ), $( this ), 'required' );
            if ( eval( formula ) == true ) {
               if ( $( this ).is( ":visible" ) ) {
                  viewTools.form.setFieldRequirement( $( this ), true );
               }
            } else {
               viewTools.form.setFieldRequirement( $( this ), false );
            }
         }
         if ( $( this ).data( 'readonly' ) !== undefined ) {
            formula = viewTools.formula._parseFormula( $( this ).data( 'readonly' ), $( this ), 'readonly' );
            if ( eval( formula ) == true ) {
               viewTools.form.setFieldReadonly( $( this ), true );
            } else {
               viewTools.form.setFieldReadonly( $( this ), false );
            }
         }
      } );
      return ret;
   } );
}
