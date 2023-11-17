generateAppraisalDialogBox = {
   id: 'GenerateAppraisalAndAppraisalItems',
   form_name: 'GenerateAppraisalAndAppraisalItems',
   input_name: 'appraisal_name',
   entrypoint: 'scheduleAppraisalAndAppraisalItems',
   enter_appraisal_name: viewTools.language.get( 'Candidatures', 'LBL_ENTER_APPRAISAL_NAME' ),

   init: function () {
      var _this = this;
      var recordData = _this.getRecordData();

      if ( undefined === $( '#' + _this.id ).get( 0 ) ) {
         $( 'body' ).append( '<div id="' + _this.id + '" >' + _this.getBodyOfDialog() + '</div>' );
      }
      $( '#' + this.id ).dialog( {
         minWidth: 500,
         minHeight: 250,
         buttons: [
            {
               text: "Ok",
               click: function () {
                  _this.valid( function () {
                     _this.ajaxRequest( recordData.record_id, recordData.module_name );
                     $( '#' + _this.id ).fadeOut( 500, function () {
                        $( '#' + _this.id ).dialog( "close" );
                     } );
                  } );
               }
            }

         ],
         close: function ( event, ui ) { }
      } );
   },
   getRecordData: function () {
      return {
         record_id: $( '#formDetailView input[name="record"]' ).val(),
         module_name: $( '#formDetailView input[name="module"]' ).val()
      };
   },
   valid: function ( callback ) {

      _this = this;
      result = true;
      viewTools.GUI.fieldErrorUnmark();
      var appraisal_name_field = $( '#' + _this.form_name + ' .' + _this.input_name );

      if ( _.isEmpty( appraisal_name_field.val() ) ) {
         viewTools.GUI.fieldErrorMark( appraisal_name_field, viewTools.language.get( 'app_strings', 'ERR_MISSING_REQUIRED_FIELDS' ) + ' ' + viewTools.language.get( 'Appraisals', 'LBL_APPRAISAL_NAME' ) );
         result = false;
      }

      if ( result ) {
         callback();
      }
   },
   ajaxRequest: function ( record_id, module_name ) {
      var appraisal_name = $( '.appraisal_name' ).val();
      var LBL_SUCCESS = viewTools.language.get( 'Candidatures', 'LBL_SUCCESS_ADDING_APPRAISAL_JOB' );
      var LBL_FAIL = viewTools.language.get( 'Candidatures', 'LBL_FAILED_ADDING_APPRAISAL_JOB' );

      var ajax_link = "index.php?entryPoint=" + this.entrypoint + "&module=" + module_name + "&appraisal_name=" + appraisal_name + "&record_id=" + record_id;

      $.get( ajax_link, function ( data ) {
      } ).done( function ( data ) {
         if ( data.indexOf( 'AppraisalJobAdded' ) != -1 ) {
            viewTools.GUI.statusBox.showStatus( LBL_SUCCESS, 'success', 1500 );
         } else {
            viewTools.GUI.statusBox.showStatus( LBL_FAIL, 'error', 1500 );
         }
      } ).fail( function () {
         viewTools.GUI.statusBox.showStatus( LBL_FAIL, 'error', 1500 );
         console.log( 'There was a problem handling Ajax request' );
      } );
      ;
   },
   getBodyOfDialog: function () {
      var recordData = this.getRecordData();
      var body = _.template( `
         <form id="<%= form_name %>" name="<%= form_name %>" style="height: 100px;">
            <b><%= LBL_ENTER_APPRAISAL_NAME %>:</b>
            <div class="col-xs-12 col-sm-12 row" type="relate">
             <input type="text" class="vt_formulaSelector appraisal_name" size="" value="" title="" autocomplete="off" id="<%= input_name %>_name"/>
            </div>
         </form>
` );
      return body( {
         MOD: SUGAR.language.languages[recordData.module_name],
         LBL_ENTER_APPRAISAL_NAME: this.enter_appraisal_name,
         form_name: this.form_name,
         input_name: this.input_name,
      } );
   }
}
