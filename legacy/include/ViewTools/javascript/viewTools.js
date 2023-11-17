if ( window.viewTools === undefined ) {
   window.viewTools = {};
}
window.viewTools.api = {
   /**
    * Function for communication  with MintHCM by controller functionality
    * Please, set params as array (in "{}" clause)
    * @param {*String} module - Name of called module
    * @param {*String} action - Name of called module action
    * @param {Function} callback - function inited after successful request
    * @param {array} dataGET - set of GET data
    * @param {array} dataPOST - set of POST data
    * @param {json/text/html/etc} dataType - type of response data
    * @param {true/false} async
    * @returns {functioncallback}
    */
   callController: function ( input ) {
      try {
         if ( input.module !== undefined && input.action !== undefined ) {
            if ( input.dataType === undefined ) {
               input.dataType = "text";
            }
            //Prepare URL_DATA
            if ( input.dataGET === undefined ) {
               input.dataGET = {};
            }
            //SET not-overwritable params
            input.dataGET.module = input.module;
            input.dataGET.action = input.action;
            input.dataGET.sugar_body_only = 1;
            //Build URLf
            var url = '';
            for ( key in input.dataGET ) {
               if ( url == '' ) {
                  url = 'index.php?';
               } else {
                  url = url + '&';
               }
               url = url + key + '=' + input.dataGET[key];
            }
            if ( input.dataPOST === undefined ) {
               input.dataPOST = {};
            }
            $.ajax( {
               dataType: input.dataType,
               url: url,
               async: input.async ?? true,
               method: 'POST',
               data: input.dataPOST,
               success: function ( response ) {
                  if ( input.callback !== undefined ) {
                     return input.callback( response );
                  }
               },
               error: function ( e ) {
                  input.error?.(e)
                  viewTools.console.log( e );
               }
            } );
         }
      } catch ( e ) {
         input.error?.(e)
         viewTools.console.log( e );
      }
   },
   /**
    * Function for communication  with MintHCM by custom/api.php
    * Please, set params as array (in "{}" clause)
    * @param {*String} module - Name of called module
    * @param {*String} action - Name of called module action
    * @param {Function} callback - function inited after successful request
    * @param {array} dataPOST - set of POST data
    * @param {json/text/html/etc} dataType - type of response data
    * @param {true/false} async
    * @returns {functioncallback}
    */
   callCustomApi: function ( input ) {
      try {
         if ( input.module !== undefined && input.action !== undefined ) {
            if ( input.dataType === undefined ) {
               input.dataType = "json";
            }
            if ( input.dataPOST === undefined ) {
               input.dataPOST = {};
            }
            input.dataPOST.module = input.module;
            input.dataPOST.action = input.action;
            input.dataPOST.is_frontend = true;
            $.ajax( {
               dataType: input.dataType,
               url: './index.php?entryPoint=viewToolsApi',
               async: input.async ?? true,
               method: 'POST',
               data: input.dataPOST,
               success: function ( response ) {
                  if ( input.callback !== undefined ) {
                     return input.callback( response );
                  }
               },
               error: function ( e ) {
                  input.error?.(e)
                  viewTools.console.log( e );
               }
            } );
         }
      } catch ( e ) {
         input.error?.(e)
         viewTools.console.log( e );
      }
   },
   asyncControllerCall: function (input) {
      return new Promise((resolve, reject) => {
         this.callController({
            ...input,
            async: true,
            callback: resolve,
            error: input.error ?? reject
         })
      })
   },
   asyncApiCall: function (input) {
      return new Promise((resolve, reject) => {
         this.callCustomApi({
            ...input,
            async: true,
            callback: resolve,
            error: input.error ?? reject
         })
      })
   },
};
window.viewTools.console = {
   log: function ( message ) {
      if ( typeof message == "object" ) {
         console.warn( 'vt_notice: response as object:' );
         console.warn( message );
      } else {
         console.warn( 'vt_notice:' + message );
      }
   }
};
/**
 * Defined actions on editview form
 * @type type
 */
window.viewTools.form = {
   afterSave: function ( callback ) {
      viewTools.cache.form_afterSave.push( callback );
   },
   beforeSave: function ( callback, enforced ) {
      if ( enforced === undefined || enforced === true ) {
         viewTools.cache.form_beforeSave_enforced.push( callback );
      } else if ( enforced !== undefined || enforced === false ) {
         viewTools.cache.form_beforeSave.push( callback );
      }
   },
   blur: function ( handler, enforced_calculated ) {
      if ( enforced_calculated === undefined || enforced_calculated !== true ) {
         enforced_calculated = false;
      }
      //If field module is not defined, try to find out which module is defined
      if ( viewTools.init.field( handler ) === true ) {
         //Dependency formula
         if ( handler.data( 'dependency' ) !== undefined ) {
            var dependency = viewTools.formula._parseFormula( handler.data( 'dependency' ), handler, 'dependency' );
            //Hide field and erase entered data
            try {
               var eval_response = eval( dependency );
            } catch ( e ) {
               viewTools.console.log( e );
            }
            if ( eval_response != true ) {
               viewTools.form.setValue( handler, '' );
               viewTools.form.lockField( handler );
               viewTools.GUI.hideField( handler );
            }
            //Show field
            else {
               viewTools.form.unlockField( handler );
               viewTools.GUI.showField( handler );
            }
         }
         //Calculated formula
         if ( handler.data( 'calculated' ) !== undefined && (enforced_calculated === true || handler.hasClass( 'vt_enforced' )) ) {
            var formula = viewTools.formula._parseFormula( handler.data( 'calculated' ), handler, 'calculated' );
            try {
               var value = eval( formula );
            } catch ( e ) {
               viewTools.console.log( e );
            }
            if ( value === false ) {
               value = '';
            }
			
            let unformatted_value=unformatNumber( value, num_grp_sep, dec_sep, 2 );
            if(!isNaN(unformatted_value)){
               value=unformatted_value;
            }
            
            viewTools.form.setValue( handler, formatNumber( value, num_grp_sep, dec_sep, 2 ) );

         }
         //Required formula
         if ( handler.data( 'required' ) !== undefined ) {
            var required = viewTools.formula._parseFormula( handler.data( 'required' ), handler, 'required' );
            try {
               var eval_response = eval( required );
            } catch ( e ) {
               viewTools.console.log( e );
            }
            if ( eval_response == true ) {
               viewTools.form.setFieldRequirement( handler, true );
            } else {
               viewTools.form.setFieldRequirement( handler, false );
            }
         }
         //Readonly formula
         if ( handler.data( 'readonly' ) !== undefined ) {
            var readonly = viewTools.formula._parseFormula( handler.data( 'readonly' ), handler, 'readonly' );
            try {
               var eval_response = eval( readonly );
            } catch ( e ) {
               viewTools.console.log( e );
            }
            if ( eval_response == true ) {
               viewTools.form.setFieldReadonly( handler, true );
            } else {
               viewTools.form.setFieldReadonly( handler, false );
            }
         }
      }
   },
   /*
    * Check for duplicates
    */
   checkFormDuplicates: function () {
      var ret = true;
      viewTools.init.field( $( '.vt_formulaSelector' ).last() );
      var modulename = viewTools.form.getModuleName( $( '.vt_formulaSelector' ).last() );
      if ( viewTools.cache.formulaDuplicateFields !== undefined && modulename !== undefined ) {

         if ( modulename !== false && viewTools.cache.formulaDuplicateFields[modulename.toLowerCase()] !== undefined ) {
            if ( viewTools.cache.ignoreDuplicates === false ) {
               viewTools.api.callCustomApi( {
                  module: 'Home',
                  action: 'checkForDuplicates',
                  dataPOST: viewTools.form.getFormValues(),
                  async: false,
                  callback: function ( response ) {
                     //Some duplicate found, display them
                     if ( response.duplicate_count > 0 ) {
                        viewTools.form.duplicatesTable( $( '.vt_formulaSelector' ).last(), response );
                        ret = false;
                     }
                  }
               } );
            }
         }
      }
      return ret;
   },
   focusOnFirstError: function () {
      if ($('.validation-message').length === 0) {
        return;
      }
      $( '.validation-message' ).first().parent().find( '.vt_formulaSelector' ).focus();
      viewTools.form.scrollToFirstError();
   },
   scrollToFirstError: function () {
      if($( '.MintHCMPopup-body' ).length && $( '.MintHCMPopup-body' ).css('display') != 'none' ) {  // scroll in popup
         $( '.MintHCMPopup-body' ).animate({
            scrollTop: $( '.validation-message' ).first().parent().offset().top - $( '.MintHCMPopup-body' ).offset().top + $( '.MintHCMPopup-body' ).scrollTop()
         }, 1000);
      }
      else {  // scroll in std editView
         $( [document.documentElement, document.body] ).animate({
            scrollTop: $( '.validation-message' ).first().parent().offset().top - $( '#toolbar' ).height() * 1.5
         }, 1000);
      }
   },
   getRecordId: function ( handler ) {
      if ( handler.data( 'recordid' ) !== undefined ) {
         return handler.data( 'recordid' );
      }
      return false;
   },
   getModuleName: function ( handler ) {
      var module_name = '';
      var form = handler.closest( 'form' );
      if ( form.length == 0 ) {
         form = handler.closest( '.detail-view' ).parent().find( '#formDetailView' );
      }
      if ( form !== undefined ) {
         module_name = form.find( 'input[name="module"]' ).val();
      }
      return module_name;
   },
   save: function ( callback ) {
      viewTools.cache.form_save = callback;
   },
   getDateTimeFormFieldNameBasedOnHandler: function ( handler ) {
      var date_time_filed_name = handler.attr( 'id' ) + '_date'
      var elem = handler.closest( 'form' ).find( '#' + date_time_filed_name );
      if ( elem.length > 0 ) {
         return elem;
      } else {
         return false;
      }
   },
   setFieldRequirement: function ( handler, isRequired, perm ) {
      var form_name = handler.closest( 'form' ).attr( 'name' );
      if ( form_name !== undefined && (isRequired === true || isRequired === false) ) {

         date_time_field_name = this.getDateTimeFormFieldNameBasedOnHandler( handler );
         if ( date_time_field_name ) {
            selector_on_field_in_form = date_time_field_name;
         } else {
            selector_on_field_in_form = handler;
         }


         var exist = _.find( validate[form_name], {0: selector_on_field_in_form.attr( 'id' )} );
         var exist_multiselect = _.find( validate[form_name], {0: selector_on_field_in_form.attr( 'id' ) + "[]"} );
         if ( exist === undefined && exist_multiselect === undefined ) {
            var obj_to_insert = {handler: selector_on_field_in_form};
            if ( perm ) {
               obj_to_insert.isRequired = false;
               if ( isRequired === true && selector_on_field_in_form.is( ":visible" ) ) {
                  obj_to_insert.isRequired = true;
               }
            }
            viewTools.cache.requiredsToSetBeforeSave.push( obj_to_insert );
         } else {
            var selector_on_field_in_form_index = this.getIndexOfFieldFromValidateArray( form_name, selector_on_field_in_form, handler );
            if ( selector_on_field_in_form_index !== false ) {
               this.setRequirementSign( selector_on_field_in_form, false );
               validate[form_name][selector_on_field_in_form_index][2] = false;
               if ( isRequired === true && selector_on_field_in_form.is( ":visible" ) ) {
                  validate[form_name][selector_on_field_in_form_index][2] = true;
                  this.setRequirementSign( selector_on_field_in_form, true );
               }
               if ( date_time_field_name ) {
                  var handler_index = this.getIndexOfFieldFromValidateArray( form_name, handler, handler );
                  if ( handler_index !== false ) {
                     validate[form_name][handler_index][2] = isRequired;
                     validate[form_name][selector_on_field_in_form_index][2] = false;
                  }
               }
            }
         }
      }
      this.setAsteriskForHandler( handler );
   },
   getIndexOfFieldFromValidateArray: function ( form_name, selector_on_field_in_form, handler ) {
      for ( var key in validate[form_name] ) {
         if ( (validate[form_name][key][0] == selector_on_field_in_form.attr( 'id' ) || validate[form_name][key][0] == selector_on_field_in_form.attr( 'id' ) + "[]") && viewTools.cache.formulaRequirements[handler.data( 'modulename' )] !== undefined ) {
            return key;
         }
      }
      return false;
   },
   setAsteriskForHandler: function ( handler ) {
      var field = $( handler['selector'] );
      if ( !field.closest( 'div.edit-view-field' ).siblings( '.label' ).hasClass( 'required' ) ) {
         field.closest( 'div.edit-view-field' ).siblings( '.label' ).html( field.closest( 'div.edit-view-field' ).siblings( '.label' ).html() + '<span class="required">*</span>' );
      }
   },
   setCacheFieldRequirement: function ( handler, required ) {
      viewTools.cache.formulaRequirements[handler.data( 'modulename' )][handler.attr( 'id' )] = required;
   },
   getCacheFieldRequirement: function ( handler ) {
      if ( viewTools.cache.formulaRequirements[handler.data( 'modulename' )] !== undefined
              && viewTools.cache.formulaRequirements[handler.data( 'modulename' )][handler.attr( 'id' )] !== undefined ) {
         return viewTools.cache.formulaRequirements[handler.data( 'modulename' )][handler.attr( 'id' )];
      }
      return false;
   },
   setRequirementSign( handler, required ) {
    var label_object = handler.closest( ".edit-view-row-item" ).children( ".label" );
    if ( required === true ) {
      label_object.append( "<span class='required'>*</span>" );
      //MintHCM #116937 Start
      label_object.addClass('bold_required_field');
      //MintHCM #116937 End
    } 
    else {
      label_object.children( "span.required" ).remove();
      //MintHCM #116937 Start
      label_object.removeClass('bold_required_field');
      //MintHCM #116937 End
    }
 },
   setFieldReadonly: function ( handler, is_readonly ) {
      var form_name = handler.closest( 'form' ).attr( 'name' );
      if ( form_name !== undefined && (is_readonly === true || is_readonly === false) ) {
         this.setFieldReadonlyAttribute( handler, false );
         if ( is_readonly === true ) {
            this.setFieldReadonlyAttribute( handler, true );
         }
      }
   },
   setFieldReadonlyAttribute: function ( handler, is_readonly ) {
      var tag_name = handler.prop( "tagName" ).toLowerCase();
      var id = handler.prop( "id" );
      var css_pointer_events = 'auto';
      var text_bg_color = '#d8f5ee';
      var select_bg_color = '#ffffff';
      var text_select_border = '1px solid #a5e8d6';
      var checkbox_border = 'none';
      if ( is_readonly ) {
         css_pointer_events = 'none';
         text_bg_color = '#f8f8f8';
         select_bg_color = '#f8f8f8';
         text_select_border = '1px solid #e2e7eb';
         checkbox_border = '1px solid #e2e7eb';
      }
      if ( tag_name === 'select' ) {
         handler.css( {
            'background-color': select_bg_color,
            'border': text_select_border,
            'pointer-events': css_pointer_events
         } );
      } else if ( $.inArray( tag_name, [ 'input', 'textarea' ] ) > -1 ) {
         var field_type = handler.prop( "type" );
         if ( field_type === 'checkbox' ) {
            handler.css( {
               'border': checkbox_border,
               'pointer-events': css_pointer_events
            } );
         } else if ( $.inArray( field_type, [ 'text', 'password', 'textarea', 'hidden' ] ) > -1 ) {
            handler.prop( 'readonly', is_readonly );
            handler.css( {
               'background-color': text_bg_color,
               'border': text_select_border
            } );
            var buttons_span = handler.parent().find( "span.id-ff.multiple" );
            if ( buttons_span.length ) {
               buttons_span.children( 'button' ).prop( 'disabled', is_readonly );
            }
            if ( id === "parent_name" ) {
               var parent_type = $( "#parent_type" );
               if ( parent_type.length ) {
                  this.setFieldReadonlyAttribute( parent_type, is_readonly );
               }
            }
            var date_field = $( "#" + id + "_date" );
            if ( date_field.length ) {
               this.setFieldReadonlyAttribute( date_field, is_readonly );
            }
            var date_trigger = $( "#" + id + "_trigger" );
            if ( date_trigger.length ) {
               date_trigger.css( 'pointer-events', css_pointer_events );
            }
            var date_hours = $( "#" + id + "_hours" );
            if ( date_hours.length ) {
               this.setFieldReadonlyAttribute( date_hours, is_readonly );
            }
            var date_minutes = $( "#" + id + "_minutes" );
            if ( date_minutes.length ) {
               this.setFieldReadonlyAttribute( date_minutes, is_readonly );
            }
         }
      }
   },
   /**
    *
    */
   setValue: function ( handler, value ) {
      //Check if field is not locked
      if ( !handler.hasClass( 'vt_Locked' ) && value !== undefined && handler.attr( 'type' ) !== 'checkbox' ) {
         //Set input/textarea/select value
         handler.val( value );
         //Delete relation hidden fields
         handler.parent().find( 'input[type=hidden]' ).each( function () {
            if ( $( this ).attr( 'id' ) !== handler.attr( 'id' ) ) {
               $( this ).val( '' );
            }
         } );
         //For readonly fields also set span html as value
         if ( handler.hasClass( 'vt_Readonly' ) ) {
            handler.parent().find( 'span.sugar_field' ).each( function () {
               $( this ).html( value );
            } );
         }
      }
   },
   /**
    * Set field disabled
    */
   lockField: function ( handler ) {
      if ( !handler.hasClass( 'vt_Locked' ) ) {
         handler.addClass( 'vt_Locked' );
      }
   },
   unlockField: function ( handler ) {
      handler.removeClass( 'vt_Locked' );
   },
   /**
    * List of functions uset to overshadow original sugar functions
    */
   function: {
      set_return: function ( popup_reply_data ) {
         set_return( popup_reply_data );
         for ( field_name in popup_reply_data.name_to_value_array ) {
            $( '#' + field_name ).each( function () {
               $( this ).blur();
            } );
         }
      }
   },
   /**
    *
    * @param {type} module_name
    * @param {type} field_id
    * @returns {window.viewTools.cache..initMappings|Boolean}
    */
   getFieldMappings: function ( module_name, field_id ) {
      if ( viewTools.cache.initMappings !== undefined && viewTools.cache.initMappings[module_name] !== undefined ) {
         if ( viewTools.cache.initMappings[module_name][field_id] !== undefined ) {
            return viewTools.cache.initMappings[module_name][field_id];
         }
      }
      return false;
   },
   /*
    * Display table with duplicates
    * @param {type} handler
    * @param {type} duplicate_array
    * @returns {undefined}
    */
   duplicatesTable: function ( handler, response ) {
      $( '#vt_duplicate_message' ).html( '' );
      var editform = handler.closest( 'form' ).parent();
      //for calendar editview
      if ( handler.closest( 'form' ).hasClass( 'vt_CalendarEditView' ) ) {
         editform = handler.closest( 'form' ).parent().parent();
         $( 'div#cal-edit-buttons' ).fadeOut( 300 );
      }
      editform.fadeOut( 300, function () {
         var table = '';
         var header = '';
         for ( label in response.duplicate_labels ) {
            if ( response.duplicate_labels[label] != null ) {
               header = header + '<td>' + response.duplicate_labels[label] + '</td>';
            }
         }
         table = table + '<tr>' + header + '</tr>';
         for ( row in response.duplicate_positions ) {
            var tr = '';
            for ( field in response.duplicate_positions[row] ) {
               if ( field != 'id' ) {
                  var tmpVal = response.duplicate_positions[row][field];
                  if ( field == 'name' ) {
                     tmpVal = '<a target="_blank" href="">' + tmpVal + '</a>';
                  }
                  tr = tr + '<td>' + tmpVal + '</td>';
               }
            }
            table = table + '<tr class="oddListRowS1">' + tr + '</tr>';
         }
         var buttons = '';
         buttons = buttons + '<button class="button vt_duplicate_btnignore">' + viewTools.language.get( 'app_strings', 'LBL_SAVE_BUTTON_TITLE' ) + '</button>';
         buttons = buttons + '<button class="button vt_duplicate_btnback">' + viewTools.language.get( 'app_strings', 'LBL_CANCEL_BUTTON_TITLE' ) + '</button>';

         $( '#vt_duplicate_message' ).append( viewTools.language.get( 'app_strings', 'LBL_VIEWTOOLS_FOUNDDUPLICATE' ) + '<table class="list view footable-loaded footable">' + table + '</table>' + buttons );
         $( '#vt_duplicate_message' ).fadeIn( 300 );
      } );
   },
   getFormValues: function () {
      var first_field = $( 'form .vt_formulaSelector' ).last();
      viewTools.init.field( first_field );
      var field_values = {};
      $( 'input[type=hidden][id$=_oldvalue]' ).each( function () {
         var tmp_field_name = $( this ).attr( 'id' );
         tmp_field_name = tmp_field_name.substr( 0, (tmp_field_name.length - 9) );
         var tmp_val = $( this ).val();
         if ( tmp_val == "null" ) {
            tmp_val = '';
         }
         field_values['vt_' + tmp_field_name] = tmp_val;
      } );
      $( '.vt_formulaSelector' ).each( function () {
         var tmp_val = viewTools.formula.valueOf( $( this ) );
         var tmp_field_name = $( this ).attr( 'id' );
         if ( tmp_field_name == "currency_id_select" ) {
            tmp_field_name = "currency_id";
         }
         field_values['vt_' + tmp_field_name] = tmp_val;
      } );
      field_values.modulename = viewTools.form.getModuleName( first_field );
      field_values.recordid = viewTools.form.getRecordId( first_field );
      return field_values;
   },
   fieldChangeEvent: function ( input ) {
      var field_id = $( input ).attr( 'id' );
      this.blur( $( input ) );
      var form_id = input.closest( 'form' ).id;
      //After finishing  operations on self, init dependend field operations
      var fieldMappings = this.getFieldMappings( $( input ).data( 'modulename' ), $( input ).attr( 'id' ) );
      if ( fieldMappings !== false && fieldMappings !== undefined ) {
         for ( var key in fieldMappings ) {
            var _this = this;
            $( '#' + form_id + ' #' + fieldMappings[key] ).each( function () {
               //Prevent looping
               if ( $( this ).attr( 'id' ) !== field_id ) {
                  _this.fieldChangeEvent( $( this ) );
               }
            } );
         }
      }
   }
};
window.viewTools.GUI = {
   /**
    *
    * @param {$(selector)} handler
    * @param {String} description
    * @param {function} callBackFunction
    * @returns {null}
    */
   fieldErrorMark: function ( handler, description, callBackFunction ) {
      add_error_style( handler.closest( 'form' ).attr( 'name' ), handler.attr( 'id' ), description );
      if ( callBackFunction !== undefined ) {
         callBackFunction();
      }
   },
   /**
    * @returns {null}
    */
   fieldErrorUnmark: function () {
      $( 'div.validation-message' ).each( function () {
         $( this ).remove();
      } );
   },
   /**
    *
    * @param {selector} handler
    * @param {validation|dependency|calculated|required} formulaType
    */
   fieldErrorFormulaMark: function ( handler, formulaType ) {
      var errorMessage = '';
      if ( handler.data( formulaType + '_message' ) !== undefined ) {
         var tmpMessages = viewTools.language.get( viewTools.form.getModuleName( handler ), handler.data( formulaType + '_message' ) );
         if ( tmpMessages == 'undefined' ) {
            tmpMessages = handler.data( formulaType + '_message' );
         }
         tmpMessages = tmpMessages.split( " " );
         errorMessage = '';
         for ( key in tmpMessages ) {
            if ( tmpMessages[key].charAt( 0 ) == '$' ) {
               var fieldLabelHandler = $( '#' + tmpMessages[key].slice( 1 ) + '_label' );
               errorMessage = errorMessage + fieldLabelHandler.html() + " ";
            } else {
               errorMessage = errorMessage + tmpMessages[key] + " ";
            }
         }
      } else {
         var field_name = "";
         try {
            var fieldLabelHandler = $( '[field=' + handler.attr( 'id' ) + ']' ).parent().find( '.label' )[0];
            field_name = $( fieldLabelHandler ).text().replace( /(\r\n|\n|\r|\*|:)/gm, "" );
         } catch ( e ) {
            viewTools.console.log( e );
         }
         errorMessage = viewTools.language.get( 'app_strings', 'LBL_VIEWTOOLS_FIELDERROR' ) + (field_name ? " \"" + field_name + "\"" : "");
      }
      window.viewTools.GUI.fieldErrorMark( handler, errorMessage );
   },
   /**
    * Init callback function when handler element will appear on site
    * @param {type} handler
    * @param {type} callback
    * @returns {undefined}
    */
   onAppear: function ( handler, callback ) {
      viewTools.cache.onAppear.push( {
         'handler': handler,
         'callback': callback
      } );
   },
   /**
    *
    * @param {$(selector)} handler
    */
   showField: function ( handler ) {
      this._rowItemVisibility( handler, 'show' );
      if ( viewTools.form.getCacheFieldRequirement( handler ) === true ) {
         viewTools.form.setFieldRequirement( handler, true );
      }
   },
   hideField: function ( handler ) {
      viewTools.form.setFieldRequirement( handler, false );
      var deleteValues = handler;
      //For datetimecombo, change way of managing error messages
      if ( handler.hasClass( 'DateTimeCombo' ) ) {
         deleteValues = handler.parent().find( '.vt_formulaSelector,.datetimecombo_time' );
      }
      //File field custom clearing value
      else if ( handler.hasClass( 'vt_File' ) ) {
         deleteValues = handler.parent().find( '#' + handler.attr( 'id' ) + '_file,#' + handler.attr( 'id' ) );
      }
      //Readonly field -,,-
      else if ( handler.hasClass( 'vt_Readonly' ) ) {
         handler.closest( '.sugar_field' ).html( '' );
      }
      //handler.closest( 'td' ).hide().closest( 'tr' ).find( 'td[id^="' + handler.attr( 'id' ) + '"]' ).hide();
      this._rowItemVisibility( handler, 'hide' );

      deleteValues.each( function () {
         if ( $( this ).attr( 'type' ) == 'checkbox' ) {
            $( this ).attr( 'checked', false );
         } else {
            $( this ).val( '' );
         }
      } );
   },
   _rowItemVisibility: function ( handler, method ) {
      if ( handler.closest( 'form' ).get( 0 ).id === "ConvertLead" ) {
         var edit_elements = handler.closest( '.edit-view-field' ).parent();
      } else {
         var edit_elements = handler.closest( '.edit-view-row-item' );
      }
      if ( method === 'hide' ) {
         edit_elements.hide();
         handler.closest( '.detail-view-row-item' ).hide();
      } else if ( method === 'show' ) {
         edit_elements.show();
         handler.closest( '.detail-view-row-item' ).show();
      }
   },
   setCountOfSubpanel: function ( subpanel_name, count, limited_count ) {
      if ( typeof count !== 'undefined' ) {
         var subpanel = $( '#whole_subpanel_' + subpanel_name );
         var title_span = subpanel.find( 'span.subpanel_title' );
         var counter = title_span.find( 'span.counter' );
         if ( count > 0 ) {
            if ( typeof limited_count !== 'undefined' && count > 10 ) {
               count = "10+";
            }
            if ( counter.length > 0 ) {
               counter.html( '(' + count + ')' );
            } else {
               var html = title_span.html();
               title_span.html( html + ' <span class="counter">(' + count + ')</span>' );
            }
            $( '#subpanel_title_' + subpanel_name ).addClass( 'subpanel-has-records' );
         } else {
            if ( counter.length > 0 ) {
               counter.remove();
            }
            $( '#subpanel_title_' + subpanel_name ).removeClass( 'subpanel-has-records' );
         }
      }
   },
   /*View Tools BEGIN #37980 ([7.7.X] Dodanie alertów w stylu ajaxStatus)*/
   statusBox: {
      showStatus: function ( message, type, miliseconds ) {
         this.initView();
         this.setCss( type );
         this.setMessage( message );
         this.show();
         this.setCloseTime( miliseconds );
      },
      initView: function () {
         if ( $( "#viewToolsToolTipDiv" ).length == 0 ) {
            this.addDiv( );
         }
      },
      setCss: function ( type ) {
         this.removeClassesFromDiv();
         this.setClassForDiv( type );
      },
      addDiv: function () {
         $( 'body' ).append( "<div id='viewToolsToolTipDiv' style='display:none;' class='viewTools-tooltip-alert' ><div id='viewToolsToolTipMsg' class='viewTools-tooltip-msg'></div></div>" );
      },
      setMessage: function ( message ) {
         if ( typeof message !== 'undefined' ) {
            $( '#viewToolsToolTipMsg' ).html( message );
         }
      },
      show: function () {
         $( '#viewToolsToolTipDiv' ).fadeIn( 150 );
      },
      hideStatus: function () {
         $( '#viewToolsToolTipDiv' ).fadeOut( 150 );
      },
      getColorClass: function ( type ) {
         var class_name = 'viewTools-tooltip-info-box';
         if ( typeof type !== 'undefined' ) {
            class_name = 'viewTools-tooltip-' + type + '-box';
         }
         return class_name;
      },
      removeClassesFromDiv: function () {
         $( '#viewToolsToolTipDiv' ).removeClass( 'viewTools-tooltip-info-box viewTools-tooltip-success-box viewTools-tooltip-error-box viewTools-tooltip-notice-box' );
      },
      setClassForDiv: function ( type ) {
         var color_class = this.getColorClass( type );
         $( '#viewToolsToolTipDiv' ).addClass( color_class );
      },
      setCloseTime: function ( miliseconds ) {
         if ( typeof miliseconds !== 'undefined' ) {
            setTimeout( this.hideStatus, miliseconds );
         }
      }
   }
   /*View Tools END #37980 ([7.7.X] Dodanie alertów w stylu ajaxStatus)*/
};
window.viewTools.date = {
   get: function ( dateString, date_time_format ) {
      if ( date_time_format === undefined ) {
         date_time_format = viewTools.date.getDateTimeFormat();
      } else {
         date_time_format = viewTools.date.getDateTimeFormat( date_time_format );
      }
      return moment( dateString, date_time_format );
   },
   /**
    * Get date format set by logged-in user
    */
   getDateFormat: function ( date_format ) {
      if ( date_format === undefined || date_format == '' ) {
         if ( viewTools.cache.InitialParams.dateFormat === undefined ) {
            viewTools.init.getDateTimeFormat();
         }
         date_format = viewTools.cache.InitialParams.dateFormat;
      }
      return date_format
              .replace( /Y/gi, 'YYYY' )
              .replace( /m/gi, 'MM' )
              .replace( /d/gi, 'DD' );
   },
   /**
    * Get time format set by logged-in user
    */
   getTimeFormat: function ( time_format ) {
      if ( time_format === undefined || time_format == '' ) {
         if ( viewTools.cache.InitialParams.timeFormat === undefined ) {
            viewTools.init.getDateTimeFormat();
         }
         time_format = viewTools.cache.InitialParams.timeFormat;
      }
      return time_format
              .replace( /i/gi, 'mm' )
              .replace( /H/g, 'HH' )
              .replace( /h/g, 'hh' );
   },
   getDateTimeFormat: function ( date_time_format ) {
      if ( date_time_format === undefined || date_time_format == '' ) {
         date_time_format = viewTools.date.getDateFormat() + ' ' + viewTools.date.getTimeFormat();
      } else {
         date_time_format = viewTools.date.getDateFormat( date_time_format );
         date_time_format = viewTools.date.getTimeFormat( date_time_format );
      }
      return date_time_format;
   },
};
/**
 * Predefined formula methods
 * @type type
 */
window.viewTools.formula = {
   /**
    * Parse formula to JS version (then you can simply eval returned function)
    * @formula
    * @handler
    * @formulatype
    */
   _parseFormula: function ( formula, handler, formulatype, formulaid ) {
      try {
         if ( formula == 1 || formula == true || formula == 0 || formula == false ) {
            return formula;
         }
         if ( formulaid !== undefined && typeof formulaid != "number" ) {
            formulaid = Number( formulaid );
         }
         var modulename = viewTools.form.getModuleName( handler );
         var fieldid = handler.attr( 'id' );
         if ( fieldid !== undefined ) {
            viewTools.init.field( handler );
            //Try to get parsed formula from cache
            if ( formulatype != null && modulename != false ) {
               if ( viewTools.cache.formula[modulename] !== undefined ) {
                  if ( viewTools.cache.formula[modulename][fieldid] !== undefined ) {
                     if ( viewTools.cache.formula[modulename][fieldid][formulatype] !== undefined ) {
                        if ( formulaid !== undefined ) {
                           if ( viewTools.cache.formula[modulename][fieldid][formulatype][formulaid] !== undefined ) {
                              return viewTools.cache.formula[modulename][fieldid][formulatype][formulaid];
                           }
                        } else {
                           return viewTools.cache.formula[modulename][fieldid][formulatype];
                        }
                     }
                  }
               }
            }
         }
         if ( formula !== undefined && formula != '' ) {
            /*
             * Find all duplicate function positions
             */
            var duplicate_positions = [ ];
            var special_functions = viewTools.cache.serversideFrontend;
            var index_of = -1;
            for ( f_key in special_functions ) {
               while ( (index_of = formula.indexOf( special_functions[f_key] + '(', index_of + 1 )) != -1 ) {
                  duplicate_positions[index_of] = special_functions[f_key].length + 1;
               }
            }
            /*
             * If found at least one duplicate declaration
             */
            if ( duplicate_positions.length > 0 ) {
               var tmpFormula = [ ];
               var tmpFormulaSectorCounter = 0;
               var tmpFormulaType = 'normal';
               var tmpBracketCounter = 0;

               for(var key=0; key<formula.length;key++){
                  //Set analised sign
                  var tmpSign = formula[key];
                  for ( didx in duplicate_positions ) {
                     if ( parseInt( key ) == (parseInt( didx ) + duplicate_positions[didx]) ) {
                        tmpFormulaSectorCounter++;
                        tmpFormulaType = 'unparsed';
                        tmpBracketCounter = 1;
                        tmpSign = '"' + tmpSign;
                     }
                  }
                  if ( tmpSign == '(' ) {
                     tmpBracketCounter++;
                  }
                  //Check duplicate end of declaration (only sign)
                  if ( tmpSign == ')' && tmpBracketCounter == 1 && tmpFormulaType == 'unparsed' ) {
                     tmpSign = '")';
                  }
                  if ( tmpFormula[tmpFormulaSectorCounter] == undefined ) {
                     tmpFormula[tmpFormulaSectorCounter] = {
                        type: tmpFormulaType,
                        string: ''
                     };
                  }
                  tmpFormula[tmpFormulaSectorCounter]['string'] = tmpFormula[tmpFormulaSectorCounter]['string'] + tmpSign;
                  //Check duplicate end of declaration (end of processing only)
                  if ( tmpSign == ')' || tmpSign == '")' ) {
                     tmpBracketCounter--;
                     if ( tmpBracketCounter <= 0 ) {
                        tmpFormulaSectorCounter++;
                        tmpFormulaType = 'normal';
                     }
                  }
               }
               //Parse splitted formulas and build right formula
               var formula = '';
               for ( key in tmpFormula ) {
                  //vt_formula
                  if ( tmpFormula[key]['type'] == 'normal' ) {
                     tmpFormula[key]['string'] = viewTools.formula._prepareFormula( tmpFormula[key]['string'], handler );
                  }
                  //Implode converted formula declaration
                  formula = formula + tmpFormula[key]['string'];
               }
            } else {
               formula = viewTools.formula._prepareFormula( formula, handler );
            }
            //Save parsed formula to cache
            if ( formulatype != null && fieldid !== undefined ) {
               if ( viewTools.cache.formula[modulename] === undefined ) {
                  viewTools.cache.formula[modulename] = {};
               }
               if ( viewTools.cache.formula[modulename][fieldid] === undefined ) {
                  viewTools.cache.formula[modulename][fieldid] = {};
               }
               if ( viewTools.cache.formula[modulename][fieldid][formulatype] === undefined ) {
                  if ( formulaid !== undefined ) {
                     if ( viewTools.cache.formula[modulename][fieldid][formulatype] === undefined ) {
                        viewTools.cache.formula[modulename][fieldid][formulatype] = [ ];
                     }
                     viewTools.cache.formula[modulename][fieldid][formulatype][formulaid] = formula;
                  } else {
                     viewTools.cache.formula[modulename][fieldid][formulatype] = formula;
                  }
               } else if ( formulaid !== undefined ) {
                  viewTools.cache.formula[modulename][fieldid][formulatype][formulaid] = formula;
               }
            }
            //Return parsed formula
            return formula;
         }
      } catch ( e ) {
         viewTools.console.log( e );
      }
      return false;
   },
   _prepareFormula: function ( formula, handler ) {
      var form_id = handler.closest( 'form' ).attr( 'id' );

      var module_name_based_on_section = '';
      if ( form_id === "ConvertLead" ) {
         var div_section = handler.closest( 'div[id^="create"]' ).get( 0 );
         if ( div_section !== undefined ) {
            var module_name_based_on_section = div_section.id.replace( /create/g, '' );
         }
      }

      return formula.replace( /\s+(?=([^']*'[^']*')*[^']*$)/g, '' ) //replace white chars but white chars in quote
              .replace( /(\w+)\(/g, 'viewTools.formula.$1(' )
              .replace( /\$(\w+),/g, 'viewTools.formula._checkSelector(\'form#' + form_id + ' #' + module_name_based_on_section + '$1\'),' )
              .replace( /\$(\w+)\)/g, 'viewTools.formula._checkSelector(\'form#' + form_id + ' #' + module_name_based_on_section + '$1\'))' )
              .replace( /^\$(\w+)$/g, 'viewTools.formula._checkSelector(\'form#' + form_id + ' #' + module_name_based_on_section + '$1\')' );
   },
   /**
    * Funcion check if selector handles field displayed on form, if not - try
    * get field value saved in record
    * @param {type} selector
    * @returns {undefined}
    */
   _checkSelector: function ( selector ) {
      if ( $( selector ).attr( 'id' ) !== undefined ) {
         return $( selector );
      } else if ( $( selector + '_oldvalue' ).attr( 'id' ) !== undefined ) {
         return $( selector + '_oldvalue' );
      }
      return false;
   },
   /*
    * Returns value of entered object
    * @param {Object} obj
    * @returns {String}
    */
   valueOf: function ( obj ) {
      try {
         if ( typeof obj === 'object' && obj instanceof jQuery ) {
            if ( obj.get( 0 ).localName === "input" ) {
               if ( obj.attr( 'type' ) === 'checkbox' ) {
                  return obj.prop( 'checked' ) ? 1 : 0;
               } else if ( obj.attr( 'type' ) == 'radio' ) {
                  obj.each( function ( ob ) {
                     if ( $( obj[ob] ).prop( 'checked' ) ) {
                        obj = $( obj[ob] );
                     }
                  } );
               } else if ( obj.parent().attr( 'type' ) == 'currency' ) {
                  return unformatNumber( obj.val(), num_grp_sep, dec_sep );
               }
            }
            obj = obj.val();
         }
         return obj;
      } catch ( e ) {
         viewTools.console.log( e );
      }
      return false;
   },
   _evalFunction: function ( handler, formulaType ) {
      if ( formulaType !== undefined ) {
         var validationMetadata = eval( 'viewTools.formulaParser.' + handler.data( formulaType + '_name' ) );
         //At first, check if validation formula exists
         var validationParams = {};
         if ( validationMetadata !== undefined ) {
            //Prepare params for validation formula
            for ( key in validationMetadata.params ) {
               //Get param value from vardef validation array
               if ( handler.data( formulaType + '_' + key ) === undefined && validationMetadata.params[key].value !== undefined ) {
                  validationParams[validationMetadata.params[key].order] = validationMetadata.params[key].value;
               } else {
                  validationParams[validationMetadata.params[key].order] = viewTools.formula._parseFormula( String( handler.data( formulaType + '_' + key ) ) );
               }
            }
            /*
             * Build validationFormula
             */
            var validationFormula = '';
            for ( key in validationParams ) {
               if ( validationFormula != '' ) {
                  validationFormula += ',';
               }
               validationFormula = validationFormula + validationParams[key];
            }
            validationFormula = 'viewTools.formula.' + validationMetadata.formulaName + '(' + validationFormula + ')';
            return eval( validationFormula );
         }
      }
   }
};
window.viewTools.init = {
   /**
    * Get params needed to operate on field
    * @returns {Boolean}
    */
   getRecordValues: function ( handler ) {
      var form = handler.closest( 'form' );
      //check if record values are already downloaded
      if ( form.find( 'input[id$="_oldvalue"],textarea[id$="_oldvalue"]' ).first().attr( 'id' ) === undefined ) {
         viewTools.api.callCustomApi( {
            module: 'Home',
            action: 'getRecordValues',
            dataPOST: {
               module_name: handler.data( 'modulename' ),
               record_id: handler.data( 'recordid' )
            },
            callback: function ( data ) {
               for ( fieldid in data ) {
                  if ( $( '#' + fieldid + '_oldvalue' ).attr( 'id' ) === undefined ) {
                     handler.closest( 'form' ).prepend( '<input type="hidden" id="' + fieldid + '_oldvalue" name="' + fieldid + '_oldvalue" value="' + data[fieldid] + '">' );
                  }
               }
            }
         } );
      }
   },
   /**
    *
    * @param {type} handler
    * @returns {undefined}
    */
   getDateTimeFormat: function () {
      if ( viewTools.cache.InitialParams.dateFormat === undefined || viewTools.cache.InitialParams.timeFormat === undefined ) {
         viewTools.api.callCustomApi( {
            module: 'Home',
            action: 'getDateTimeFormat',
            async: false,
            callback: function ( data ) {
               //
               if ( data.dateFormat !== undefined && viewTools.cache.InitialParams.dateFormat === undefined ) {
                  viewTools.cache.InitialParams.dateFormat = data.dateFormat;
               }
               //
               if ( data.timeFormat !== undefined && viewTools.cache.InitialParams.timeFormat === undefined ) {
                  viewTools.cache.InitialParams.timeFormat = data.timeFormat;
               }
            }
         } );
      }
   },
   /*
    * Define default params needed to operate with vt_formula
    * @returns {undefined}
    */
   field: function ( handler ) {
      //
      var ret = true;
      if ( handler.data( 'recordid' ) === undefined ) {
         var record_id = handler.closest( 'form' ).find( 'input[type=hidden][name=record]' ).val();
         if ( record_id !== undefined ) {
            handler.data( 'recordid', record_id );
         } else {
            ret = false;
         }
      }
      //
      if ( handler.data( 'modulename' ) === undefined ) {
         //Get module name
         var modulename = handler.closest( 'form' ).find( 'input[type=hidden][name=module]' ).val();
         //If some problem occured, check if record is edited from calendar module
         if ( modulename === undefined ) {
            var modulename = handler.closest( 'form' ).find( 'input[type=hidden][name=current_module]' ).val();
         }
         if ( modulename !== undefined ) {
            handler.data( 'modulename', modulename.toLowerCase() );
            //
            if ( handler.closest( 'form' ).data( 'modulename' ) === undefined ) {
               handler.closest( 'form' ).data( 'modulename', modulename.toLowerCase() );
               //For calendar editview change way of hiding editform
               if ( handler.closest( 'form' ).hasClass( 'vt_CalendarEditView' ) ) {
                  handler.closest( 'form' ).parent().parent().after( '<div id="vt_duplicate_message" style="display:none;"></div>' );
               } else {
                  handler.closest( 'form' ).parent().after( '<div id="vt_duplicate_message" style="display:none;"></div>' );
               }
            }
            if ( ret !== false && record_id !== "" ) {
               viewTools.init.getRecordValues( handler );
            }
         } else {
            ret = false;
         }
      }
      return ret;
   },
   validation: {
      checkForm: function ( callback ) {
         var ret = true;
         $( '.vt_formulaSelector' ).each( function () {
            /*
             * Check if validation is set
             * @type Object
             */
            if ( $( this ).data( 'validation_name' ) !== undefined ) {
               if ( viewTools.formula._evalFunction( $( this ), 'validation' ) == false ) {
                  viewTools.GUI.fieldErrorFormulaMark( $( this ), 'validation' );
                  ret = false;
               } else {
                  window.viewTools.GUI.fieldErrorUnmark( $( this ) );
               }
            } else if ( $( this ).data( 'validation' ) !== undefined ) {
               var validation_array = $( this ).data( 'validation' ).split( ';' );

               for ( var validation_idx in validation_array ) {
                  formula = viewTools.formula._parseFormula( validation_array[validation_idx] );
                  if ( eval( formula ) == false ) {
                     viewTools.GUI.fieldErrorFormulaMark( $( this ), 'validation' );
                     ret = false;
                  }
               }

            }
         } );
         if ( ret !== false ) {
            window.viewTools.GUI.fieldErrorUnmark( $( this ) );
            return callback();
         }
         return false;
      }
   },
};
window.viewTools.language = {
   get: function ( module, label ) {
      if ( typeof SUGAR.language.languages[module] == "undefined" ) {
         viewTools.api.callCustomApi( {
            module: 'Home',
            action: 'getLanguage',
            dataPOST: {"target_module": module},
            async: false,
            callback: function ( response ) {
               if ( typeof response == "object" ) {
                  SUGAR.language.setLanguage( module, response );
               } else {
                  viewTools.console.log( "viewTools is not able to get labels for " + module );
               }
            }
         } );
      }
      return SUGAR.language.get( module, label );
   }
};
if ( viewTools.cache === undefined ) {
   //Cache for parsed formulas
   viewTools.cache = {
      enforceFields: {},
      formula: {},
      fieldRequirements: {},
      recordValues: {},
      formulaRequirements: {},
      InitialParams: {},
      serversideFrontend: [ ],
      form_beforeSave: [ ],
      form_beforeSave_enforced: [ ],
      form_afterSave: [ ],
      save_action: [ ],
      ignoreDuplicates: false,
      onAppear: [ ],
      requiredsToSetBeforeSave: [ ],
   };
}