<?php

/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 * ****************************************************************************** */

$viewdefs ['Calls'] = array(
   'EditView' => array(
      'templateMeta' => array(
         'includes' => array(
            array('file' => 'modules/Reminders/Reminders.js'),
         ),
         'maxColumns' => '2',
         'form' => array(
            'hidden' => array(
               '<input type="hidden" name="isSaveAndNew" value="false">',
               '<input type="hidden" name="send_invites">',
               '<input type="hidden" name="user_invitees">',
               '<input type="hidden" name="contact_invitees">',
               '<input type="hidden" name="candidate_invitees">',
               '<input type="hidden" name="resource_invitees">',
            ),
            'buttons' => array(
               array(
                  'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_HEADER" ' .
                  'accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" ' .
                  'onclick="SUGAR.calls.fill_invitees();document.EditView.action.value=\'Save\'; ' .
                  'document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && ' .
                  '$smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; ' .
                  '{/if}formSubmitCheck();;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
               ),
               'CANCEL',
               array(
                  'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="SAVE_SEND_HEADER" class="button" ' .
                  'onclick="document.EditView.send_invites.value=\'1\';SUGAR.calls.fill_invitees();' .
                  'document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';' .
                  'document.EditView.return_module.value=\'{$smarty.request.return_module}\';formSubmitCheck();;" ' .
                  'type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
               ),
               array(
                  'customCode' => '{if $fields.status.value != "Held"}<input ' .
                  'title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="CLOSE_CREATE_HEADER" ' .
                  'accessKey="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_KEY}" class="button" ' .
                  'onclick="SUGAR.calls.fill_invitees(); document.EditView.status.value=\'Held\'; ' .
                  'document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Calls\'; ' .
                  'document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; ' .
                  'document.EditView.return_action.value=\'EditView\'; ' .
                  'document.EditView.return_id.value=\'{$fields.id.value}\'; ' .
                  'formSubmitCheck();" type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
               ),
            ),
            'buttons_footer' => array(
               array(
                  'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_FOOTER" ' .
                  'accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" ' .
                  'onclick="SUGAR.calls.fill_invitees();document.EditView.action.value=\'Save\'; ' .
                  'document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && ' .
                  '$smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; ' .
                  '{/if}formSubmitCheck();;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
               ),
               'CANCEL',
               array(
                  'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="SAVE_SEND_FOOTER" class="button" ' .
                  'onclick="document.EditView.send_invites.value=\'1\';SUGAR.calls.fill_invitees();' .
                  'document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';' .
                  'document.EditView.return_module.value=\'{$smarty.request.return_module}\';formSubmitCheck();;" ' .
                  'type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
               ),
               array(
                  'customCode' => '{if $fields.status.value != "Held"}<input ' .
                  'title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="CLOSE_CREATE_FOOTER" ' .
                  'accessKey="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_KEY}" class="button" ' .
                  'onclick="SUGAR.calls.fill_invitees(); document.EditView.status.value=\'Held\'; ' .
                  'document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Calls\'; ' .
                  'document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; ' .
                  'document.EditView.return_action.value=\'EditView\'; ' .
                  'document.EditView.return_id.value=\'{$fields.id.value}\'; formSubmitCheck();" ' .
                  'type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
               ),
            ),
            'headerTpl' => 'modules/Calls/tpls/header.tpl',
            'buttons_footer' => array(
               array(
                  'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id ="SAVE_FOOTER" ' .
                  'accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.calls.fill_invitees();' .
                  'document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; ' .
                  '{if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}' .
                  'document.EditView.return_id.value=\'\'; {/if} formSubmitCheck();"type="button" ' .
                  'name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
               ),
               'CANCEL',
               array(
                  'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="save_and_send_invites_footer" class="button" onclick="document.EditView.send_invites.value=\'1\';SUGAR.calls.fill_invitees();document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';document.EditView.return_module.value=\'{$smarty.request.return_module}\'; formSubmitCheck();"type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
               ),
               array(
                  'customCode' => '{if $fields.status.value != "Held"}<input title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="close_and_create_new_footer" class="button" onclick="SUGAR.calls.fill_invitees(); document.EditView.status.value=\'Held\'; document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Meetings\'; document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; document.EditView.return_action.value=\'EditView\'; document.EditView.return_id.value=\'{$fields.id.value}\'; formSubmitCheck();"type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
               ),
            ),
            'footerTpl' => 'modules/Calls/tpls/footer.tpl',
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
         'javascript' => '{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
<script type="text/javascript">{$JSON_CONFIG_JAVASCRIPT}</script>
<script>toggle_portal_flag();function toggle_portal_flag()  {ldelim} {$TOGGLE_JS} {rdelim}
function formSubmitCheck(){ldelim}var duration=true;if(typeof(isValidDuration)!="undefined"){ldelim}duration=isValidDuration();{rdelim}if(check_form(\'EditView\') && duration){ldelim}SUGAR.ajaxUI.submitForm("EditView");{rdelim}{rdelim}</script>',
         'useTabs' => false,
         'tabDefs' => array(
            'LBL_CALL_INFORMATION' => array(
               'newTab' => false,
               'panelDefault' => 'expanded',
            ),
         ),
      ),
      'panels' => array(
         'lbl_call_information' => array(
            array(
               array(
                  'name' => 'name',
               ),
               array(
                  'name' => 'status',
                  'fields' => array(
                     array(
                        'name' => 'direction',
                     ),
                     array(
                        'name' => 'status',
                     ),
                  ),
               ),
            ),
            array(
               array(
                  'name' => 'date_start',
                  'displayParams' => array(
                     'updateCallback' => 'SugarWidgetScheduler.update_time();',
                  ),
                  'label' => 'LBL_DATE_TIME',
               ),
               array(
                  'name' => 'parent_name',
                  'label' => 'LBL_LIST_RELATED_TO',
               ),
            ),
            array(
               array(
                  'name' => 'duration_hours',
                  'label' => 'LBL_DURATION',
                  'customCode' => '{literal}<script type="text/javascript">function isValidDuration() { form = document.getElementById(\'EditView\'); if ( form.duration_hours.value + form.duration_minutes.value <= 0 ) { alert(\'{/literal}{$MOD.NOTICE_DURATION_TIME}{literal}\'); return false; } return true; }</script>{/literal}<input id="duration_hours" name="duration_hours" size="2" maxlength="2" type="text" value="{$fields.duration_hours.value}" onkeyup="SugarWidgetScheduler.update_time();"/>{$fields.duration_minutes.value}&nbsp;<span class="dateFormat">{$MOD.LBL_HOURS_MINUTES}</span>',
               ),
//          array (
//            'name' => 'reminder_time',
//            'customCode' => '{include file="modules/Meetings/tpls/reminders.tpl"}',
//            'label' => 'LBL_REMINDER',
//          ),
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
