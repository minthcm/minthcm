var combo_date_start = null;
generateOnboardingOffboarding = {
   id: 'generateOnboardingOffboarding',
   form_name: 'GenerateOnboardingOffboarding',
   entrypoint: 'scheduleGenerateOnboardingOffboarding',
   relate_field_name: 'employee',
   relate_field_target_module: 'Employees',
   init: function () {
      var _this = this;
      viewTools.GUI.fieldErrorUnmark();
      if ( undefined == $( '#' + _this.id ).get( 0 ) ) {
         $( 'body' ).append( '<div id="' + _this.id + '" >' + _this.getBodyOfDialog() + '</div>' );
      }
      _this.prepareSqsObjects();
      _this.prepareCalendar();
      $( '#' + this.id ).dialog( {
         minWidth: 450,
         minHeight: 250,
         buttons: [
            {
               text: "OK",
               click: function () {
                  _this.valid( function ( employee_id, date_start ) {
                     _this.sendRecords( $( 'input[name=module]:not(.form-control)' ).val(), $( 'input[name=record]' ).val(), employee_id, date_start, $( '.favorite' ).attr( 'record_id' ) );
                     $( '#' + _this.id ).dialog( "close" );
                  } );
               }
            }
         ],
         close: function ( event, ui ) { }
      } );
   },
   valid: function ( callback ) {
      _this = this;
      result = true;
      viewTools.GUI.fieldErrorUnmark();
      var employee_field = $( '#' + _this.form_name + ' #' + _this.relate_field_name + '_id' );
      var date_start_date = $( '#date_start_date' );
      if ( _.isEmpty( employee_field.val() ) ) {
         viewTools.GUI.fieldErrorMark( employee_field, viewTools.language.get( 'app_strings', 'ERR_MISSING_REQUIRED_FIELDS' ) + ' ' + viewTools.language.get( 'OnboardingTemplates', 'LBL_EMPLOYEE_NAME' ) );
         result = false;
      }
      if ( _.isEmpty( date_start_date.val() ) ) {
         viewTools.GUI.fieldErrorMark( date_start_date, viewTools.language.get( 'app_strings', 'ERR_MISSING_REQUIRED_FIELDS' ) + ' ' + viewTools.language.get( 'OnboardingTemplates', 'LBL_START_DATE' ) );
         result = false;
      }
      if ( result ) {
         var date_start = date_start_date.val() + " " + $( '#date_start_hours' ).val() + ":" + $( '#date_start_minutes' ).val();
         if ( $( '#date_start_meridiem' ).length === 1 ) {
            var date_start = date_start + $( '#date_start_meridiem' ).val();
         }
         callback( employee_field.val(), date_start );
      }
   }
   ,
   sendRecords: function ( module_name, template_id, employee_id, date_start, record_id ) {
      var url_ = "index.php?entryPoint=" + this.entrypoint + "&module_name=" + module_name + "&template_id=" + template_id + "&employee_id=" + employee_id + "&date_start=" + date_start + "&record_id=" + record_id;
      $.ajax( {
         type: "POST",
         async: true,
         url: url_,
         success: function ( data ) {
            if ( data ) {
               viewTools.GUI.statusBox.showStatus( viewTools.language.get( 'OnboardingTemplates', 'LBL_GENERATE_SUCCESS' ), 'success', 5000 );
            } else {
               viewTools.GUI.statusBox.showStatus( viewTools.language.get( 'OnboardingTemplates', 'LBL_GENERATE_ERROR' ), 'error', 5000 );
            }
         },
         error: function ( jqXHR, exception ) {
            viewTools.GUI.statusBox.showStatus( viewTools.language.get( 'OnboardingTemplates', 'LBL_GENERATE_ERROR' ), 'error', 5000 );
         }
      } );
   },
   getBodyOfDialog: function () {
      var body = _.template( $( '#generate-onboarding-offboarding-template' ).html() );
      return body( {
         MOD: SUGAR.language.languages[$( 'input[name=module]:not(.form-control)' ).val()],
         relate_field_name: this.relate_field_name,
         form_name: this.form_name,
         relate_field_target_module: this.relate_field_target_module,
      } );
   },
   prepareSqsObjects: function () {
      sqs_objects = [ ];
      var key = generateOnboardingOffboarding.form_name + "_" + generateOnboardingOffboarding.relate_field_name + "_name";
      var relate_field_name = generateOnboardingOffboarding.relate_field_name + "_name";
      var relate_field_id = generateOnboardingOffboarding.relate_field_name + "_id";
      sqs_objects[key] = {
         form: generateOnboardingOffboarding.form_name,
         method: "query",
         modules: [
            generateOnboardingOffboarding.relate_field_target_module
         ],
         group: "and",
         field_list: [ "name", "id" ],
         populate_list: [
            relate_field_name,
            relate_field_id
         ],
         conditions: [ {"name": "name", "op": "like_custom", "end": "%", "value": ""} ],
         required_list: [
            relate_field_id
         ],
         order: "name",
         limit: "30",
         no_match_text: "Nie pasuje"
      };
      enableQS();
   },
   prepareCalendar: function () {
      combo_date_start = new Datetimecombo( "", "date_start", _user_time_format, "0", '', false, true, '' );
      var text = combo_date_start.html( "" );
      document.getElementById( "date_start_time_section" ).innerHTML = text;
      eval( combo_date_start.jsscript( "" ) );
      YAHOO.util.Event.onDOMReady( function ()
      {
         Calendar.setup( {
            onClose: update_date_start,
            inputField: "date_start_date",
            form: "GenerateOnboardingOffboarding",
            ifFormat: _calendar_format,
            daFormat: _calendar_format,
            button: "date_start_trigger",
            singleClick: true,
            step: 1,
            weekNumbers: false,
            startWeekday: _calendar_fdow,
            comboObject: combo_date_start
         } );
         combo_date_start.update( false );
      } );
   }

};
