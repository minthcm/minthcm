<?php

$viewdefs ['Meetings'] = array(
   'EditView' => array(
      'templateMeta' => array(
         'includes' => array(
            array('file' => 'modules/Reminders/Reminders.js'),
         ),
         'maxColumns' => '2',
         'form' => array(
            'hidden' => array(
               '<input type="hidden" name="isSaveAndNew" value="false">',
               '<input type="hidden" name="candidate_invitees">',
               '<input type="hidden" name="resource_invitees">',
            ),
            'buttons' => array(
               array(
                  'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id ="SAVE_HEADER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.meetings.fill_invitees();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if} formSubmitCheck();"type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
               ),
               'CANCEL',
               array(
                  'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="save_and_send_invites_header" class="button" onclick="document.EditView.send_invites.value=\'1\';SUGAR.meetings.fill_invitees();document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';document.EditView.return_module.value=\'{$smarty.request.return_module}\'; formSubmitCheck();"type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
               ),
               array(
                  'customCode' => '{if $fields.status.value != "Held"}<input title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="close_and_create_new_header" class="button" onclick="SUGAR.meetings.fill_invitees(); document.EditView.status.value=\'Held\'; document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Meetings\'; document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; document.EditView.return_action.value=\'EditView\'; document.EditView.return_id.value=\'{$fields.id.value}\'; formSubmitCheck();"type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
               ),
            ),
            'headerTpl' => 'modules/Meetings/tpls/header.tpl',
            'buttons_footer' => array(
               array(
                  'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id ="SAVE_FOOTER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.meetings.fill_invitees();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if} formSubmitCheck();"type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
               ),
               'CANCEL',
               array(
                  'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="save_and_send_invites_footer" class="button" onclick="document.EditView.send_invites.value=\'1\';SUGAR.meetings.fill_invitees();document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';document.EditView.return_module.value=\'{$smarty.request.return_module}\'; formSubmitCheck();"type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
               ),
               array(
                  'customCode' => '{if $fields.status.value != "Held"}<input title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="close_and_create_new_footer" class="button" onclick="SUGAR.meetings.fill_invitees(); document.EditView.status.value=\'Held\'; document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Meetings\'; document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; document.EditView.return_action.value=\'EditView\'; document.EditView.return_id.value=\'{$fields.id.value}\'; formSubmitCheck();"type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
               ),
            ),
            'footerTpl' => 'modules/Meetings/tpls/footer.tpl',
         ),
         'widths' => array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'javascript' => '<script type="text/javascript">{$JSON_CONFIG_JAVASCRIPT}</script>
{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
<script>toggle_portal_flag();function toggle_portal_flag()  {ldelim} {$TOGGLE_JS} {rdelim} 
function formSubmitCheck(){ldelim}if(check_form(\'EditView\')){ldelim}document.EditView.submit();{rdelim}{rdelim}</script>',
         'useTabs' => false,
      ),
      'panels' => array(
         'lbl_meeting_information' => array(
            array(
               array(
                  'name' => 'name',
               ),
               array(
                  'name' => 'status',
                  'fields' => array(
                     array(
                        'name' => 'status',
                     ),
                  ),
               ),
            ),
            array(
               array(
                  'name' => 'type',
                  'label' => 'LBL_TYPE',
               ),
            ),
            array(
               array(
                  'name' => 'date_start',
                  'type' => 'datetimecombo',
                  'displayParams' => array(
                     'required' => true,
                     'updateCallback' => 'SugarWidgetScheduler.update_time();',
                  ),
               ),
               array(
                  'name' => 'parent_name',
                  'label' => 'LBL_LIST_RELATED_TO',
               ),
            ),
            array(
               array(
                  'name' => 'date_end',
                  'type' => 'datetimecombo',
                  'displayParams' => array(
                     'required' => true,
                     'updateCallback' => 'SugarWidgetScheduler.update_time();',
                  ),
               ),
               array(
                  'name' => 'location',
                  'comment' => 'Meeting location',
                  'label' => 'LBL_LOCATION',
               ),
            ),
            array(
               array(
                  'name' => 'duration',
                  'customCode' => '
@@FIELD@@
<input id="duration_hours" name="duration_hours" type="hidden" value="{$fields.duration_hours.value}">
<input id="duration_minutes" name="duration_minutes" type="hidden" value="{$fields.duration_minutes.value}">
{sugar_getscript file="modules/Meetings/duration_dependency.js"}
<script type="text/javascript">
   var date_time_format = "{$CALENDAR_FORMAT}";
   {literal}
   SUGAR.util.doWhen(function(){return typeof DurationDependency != "undefined" && typeof document.getElementById("duration") != "undefined"}, function(){
      var duration_dependency = new DurationDependency("date_start","date_end","duration",date_time_format);
      initEditView(YAHOO.util.Selector.query(\'select#duration\')[0].form);
   });
   {/literal}
</script>            
            ',
                  'customCodeReadOnly' => '{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
               ),
            ),
            array(
               array(
                  'name' => 'reminders',
                  'customCode' => '{include file="modules/Reminders/tpls/reminders.tpl"}',
                  'label' => 'LBL_REMINDERS',
               ),
            ),
            array(
               array(
                  'name' => 'description',
                  'comment' => 'Full text of the note',
                  'label' => 'LBL_DESCRIPTION',
               ),
            ),
         ),
         'LBL_REPEAT_TAB' => array(
            array(
               array(
                  'name' => 'repeat_type',
                  'hideLabel' => true,
                  'customCode' => '{include file="modules/Meetings/tpls/RepeatPanelEditView.tpl"}',
               ),
            ),
         ),
         'LBL_PANEL_ASSIGNMENT' => array(
            array(
               array(
                  'name' => 'assigned_user_name',
                  'label' => 'LBL_ASSIGNED_TO_NAME',
               ),
            ),
         ),
      ),
   ),
);

