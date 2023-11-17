
{if $show_edit_all_recurrences}
    <div id="edit_all_recurrences_block">
        <button type="button" id="btn-edit-all-recurrences" onclick="editAllRecurrences()"> {$MOD.LBL_EDIT_ALL_RECURRENCES} </button>
        <button type="button" id="btn-remove-all-recurrences" onclick="deleteAllRecurences()"> {$MOD.LBL_REMOVE_ALL_RECURRENCES} </button>
    </div>
    <script type="text/javascript">
        {literal}
            function deleteAllRecurences() {

               if ( confirm( '{/literal}{$MOD.LBL_CONFIRM_REMOVE_ALL_RECURRING}{literal}' ) ) {
        {/literal}
                  var record = '{$fields.repeat_parent_id.value|default:$fields.id.value}';
                  location.href = 'index.php?module=Meetings&action=resetPeriodicity&record=' + record;
        {literal}
               }
            }

            function editAllRecurrences() {
        {/literal}
               location.href = 'index.php?module=Meetings&action=EditView&record=&show_edit_all_recurrences=1';
        {literal}
            }
        {/literal}
    </script>
{else}
    <div id="cal-repeat-block">

        <input type="hidden" name="repeat_parent_id" value="{$fields.repeat_parent_id.value}">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_TYPE}:</td>
                <td width="37.5%" valign="top">
                    <select name="repeat_type" onchange="toggle_repeat_type();">{html_options options=$APPLIST.repeat_type_dom selected=$fields.repeat_type.value}</select>
                </td>
            </tr>
            <tr id="repeat_interval_row" style="display: none;">
                <td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_INTERVAL}:</td>
                <td width="37.5%" valign="top">
                    <select name="repeat_interval">{html_options options=$repeat_intervals selected=$fields.repeat_interval.value|default:'1'}</select> <span id="repeat-interval-text"></span>
                </td>
            </tr>
            <tr id="repeat_end_row" style="display: none;">
                <td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_END}:</td>
                <td width="37.5%" valign="top">
                    <div>
                        <input type="radio" name="repeat_end_type" value="number" id="repeat_count_radio" checked onclick="toggle_repeat_end();" style="position: relative; top: -5px;"> {$MOD.LBL_REPEAT_END_AFTER}
                        <input type="input" size="3" name="repeat_count" value="{$fields.repeat_count.value|default:'10'}"> {$MOD.LBL_REPEAT_OCCURRENCES}
                    </div>
                    <div>
                        <input type="radio" name="repeat_end_type" id="repeat_until_radio" value="date" onclick="toggle_repeat_end();" style="position: relative; top: -5px;"> {$MOD.LBL_REPEAT_END_BY}
                        <input type="input" size="11" maxlength="10" id="repeat_until_input" name="repeat_until" value="{$fields.repeat_until.value|default:''}" disabled>
                        <img border="0" src="index.php?entryPoint=getImage&imageName=jscalendar.gif" alt="{$APP.LBL_ENTER_DATE}" id="repeat_until_trigger" align="absmiddle" style="display: none;">

                        <script type="text/javascript">
                            Calendar.setup({literal} {{/literal}
                               inputField: "repeat_until_input",
                               ifFormat: "{$CALENDAR_FORMAT}",
                               daFormat: "{$CALENDAR_FORMAT}",
                               button: "repeat_until_trigger",
                               singleClick: true,
                               dateStr: "",
                               step: 1,
                               startWeekday: {$CALENDAR_FDOW|default:'0'},
                               weekNumbers: false
                            {literal}}{/literal} );
                        </script>
                    </div>
                </td>
            </tr>

            <tr id="repeat_dow_row" style="display: none;">
                <td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_DOW}:</td>
                <td width="37.5%" valign="top">
                    {foreach name=dow from=$dow key=i item=d}
                        {$d.label} <input type="checkbox" id="repeat_dow_{$d.index}" {$repeat_dow_checked} onchange="assignRepeatDow()" style="margin-right: 10px;">
                    {/foreach}
                    <input type="hidden" id="repeat_dow" name="repeat_dow" value="{$fields.repeat_dow.value}" />
                    <input type="hidden" id="edit_all_recurrences" name="edit_all_recurrences" value="true" />
                </td>
            </tr>

        </table>

    </div>

    <script type="text/javascript">
        {$ALSRI}
    </script>
    <script type="text/javascript" src="modules/Meetings/tpls/RepeatPanelEditView.js"></script>
{/if}