<script type="text/javascript">
    var dashlet_id = '{$id}';
    {literal}
        function clearReportId() {
           $( '#report_id' ).val( '' );
        }
        function clearReportInput() {
           clearReportId();
           $( '#report_name' ).val( '' );
        }
        function openReportInputPopup() {
           var popup_request_data = {
              call_back_function: "catchReportInputPopupData",
              field_to_name_array: {
                 id: 'report_id',
                 name: 'report_name',
              },
           };
           open_popup( 'KReports', 800, 850, '', true, true, popup_request_data );
        }
        function catchReportInputPopupData( data ) {
           $.each( data.name_to_value_array, function ( field, value ) {
              $( '#' + field ).val( value );
           } )
        }
        $( document ).ready( function () {
           var form_name = 'configure_' + dashlet_id;
           if ( typeof sqs_objects !== 'object' ) {
              sqs_objects = [ ];
           }
           sqs_objects["report_name"] = {
              "form": form_name,
              "method": "query",
              "modules": [ "KReports" ],
              "field_list": [ "name", "id" ],
              "populate_list": [ "report_name", "report_id" ],
              "required_list": [ "report_id" ],
              "conditions": [ {
                    "name": "name",
                    "op": "like_custom",
                    "end": "%",
                    "value": ""
                 } ],
              "order": "name",
              "limit": "30",
              "no_match_text": "No Match",
              "post_onblur_function": "",
           };
           enableQS( true );
        } );
    {/literal}
</script>

<div style='width:100%'>
    <form name='configure_{$id}' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm( "configure_{$id}", SUGAR.mySugar.uncoverPage );'>
        <input type='hidden' name='id' value='{$id}'>
        <input type='hidden' name='module' value='Home'>
        <input type='hidden' name='action' value='ConfigureDashlet'>
        <input type='hidden' name='to_pdf' value='true'>
        <input type='hidden' name='configure' value='true'>

        <div style='padding:10px'>
            <label for='title'>{$dashletStrings.LBL_DASHLET_NAME}</label><br />
            <input type="text" name="title" size='30' maxlength='80' value='{$title}' />
        </div>
        <div style='padding:10px'>
            <label for='report_name'>{$dashletStrings.LBL_DASHLET_RAPORT}</label><br />
            <input class='sqsEnabled' autocomplete='off' type='text' name='report_name' oninput='clearReportId()' id='report_name' size='30' tabindex='116' value='{$report_name}' />
            <input type='hidden' name='report_id' id='report_id'  maxlength='50' value='{$report_id}' />
            <span class='id-ff'><button title='{$APP.LBL_SELECT_BUTTON_LABEL}' accessKey='{$APP.LBL_SELECT_BUTTON_KEY}' type='button' tabindex='116' class='button' value='{$APP.LBL_SELECT_BUTTON_LABEL}' onclick='openReportInputPopup()'><img src='themes/SuiteP/images/id-ff-select.png' alt='{$APP.LBL_SELECT_BUTTON_LABEL}'></button></span>
            <span class='id-ff'><button type='button' class='button' value='{$APP.LBL_CLEAR_BUTTON_LABEL}' tabindex='116' onclick='clearReportInput( )'><img src='themes/SuiteP/images/id-ff-clear.png' alt='{$APP.LBL_CLEAR_BUTTON_LABEL}'></button></span>
        </div>
        <div style='padding:10px'>
            <label>{$dashletStrings.LBL_DASHLET_VIEWOPTIONS}</label><br />
            <input type="checkbox" name="show_chart" {if $show_chart}checked{/if} />&nbsp;{$dashletStrings.LBL_VIEWOPTION_SHOWCHART}<br />
            <input type="checkbox" name="show_data" {if $show_data}checked{/if} />&nbsp;{$dashletStrings.LBL_VIEWOPTION_SHOWDATA}<br />
            <input type="checkbox" name="show_filters" {if $show_filters}checked{/if} />&nbsp;{$dashletStrings.LBL_VIEWOPTION_SHOWFILTERS}<br />
        </div>
        <div style='padding:10px'>
            <label for='height'>{$dashletStrings.LBL_DASHLET_HEIGHT}</label><br />
            <input type="text" name="height" size='10' maxlength='3' value='{$height}' />
            <p><em>{$dashletStrings.LBL_DASHLET_HEIGHT_HELP}</em></p>
        </div>
        {if $isRefreshable}
            <div style='padding:10px'>
                <label for='autoRefresh'>{$dashletStrings.LBL_DASHLET_AUTOREFRESH}</label><br />
                <select name='autoRefresh'>
                    {html_options options=$autoRefreshOptions selected=$autoRefreshSelect}
                </select>
            </div>
            <div style='padding:10px'>
                <input type='submit' class='button' value='{$APP.LBL_SAVE_BUTTON_LABEL}'>
            </div>
        {/if}
    </form>
</div>