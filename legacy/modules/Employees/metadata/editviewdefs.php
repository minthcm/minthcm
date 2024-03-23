<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
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
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$viewdefs ['Employees'] = array(
   'EditView' =>
   array(
      'templateMeta' =>
         array(
            'form' => array (
               'buttons' => array(
                  array(
                     'customCode' => true,
                     'sugar_html' => array(
                        'type' => 'button',
                        'value' => '{$APP.LBL_SAVE_BUTTON_TITLE}',
                        'htmlOptions' => array(
                              'class' => 'button primary',
                              'name' => 'button',
                              'id' => 'SAVE',
                              'title' => '{$APP.LBL_SAVE_BUTTON_TITLE}',
                              'accessKey' => '{$APP.LBL_SAVE_BUTTON_KEY}',
                              'onClick' => "var _form = document.getElementById('EditView'); _form.action.value='Save'; if(check_form('EditView') && getSupervisedUnitsIfEmployeeIsSupervisor())SUGAR.ajaxUI.submitForm(_form);return false;",
                        ),
                        'template' => '{if $bean->aclAccess("save")}[CONTENT]{/if}',
                     ),
                  ),
                  'CANCEL',
            ),
         ),
         'maxColumns' => '2',
         'widths' =>
         array(
            array(
               'label' => '10',
               'field' => '30',
            ),
            array(
               'label' => '10',
               'field' => '30',
            ),
         ),
         'useTabs' => true,
         'tabDefs' =>
         array(
            'DEFAULT' =>
            array(
               'newTab' => true,
               'panelDefault' => 'expanded',
            ),
         ),
         'includes' => array(
            array(
                'file' => 'modules/Employees/js/view.edit.js',
            ),
        ),
      ),
      'panels' =>
      array(
         'default' =>
         array(
            array(
               'employee_status',
               'photo',
            ),
            array(
               'first_name',
               array(
                  'name' => 'last_name',
                  'displayParams' =>
                  array(
                     'required' => true,
                  ),
               ),
            ),
            array(
               array(
                  'name' => 'position_name',
                  'customCode' => '<input type="text" name="{$fields.position_name.name}" class="sqsEnabled" tabindex="0" id="{$fields.position_name.name}" size="" value="{$fields.position_name.value}" title="" autocomplete="off" >{$REPORTS_TO_JS}<input type="hidden" name="{$fields.position_id.name}" id="{$fields.position_id.name}" value="{$fields.position_id.value}"> <span class="id-ff multiple"><button type="button" name="btn_{$fields.position_name.name}" tabindex="0" title="{$APP.LBL_SELECT_BUTTON_TITLE}" class="button firstChild" value="{$APP.LBL_SELECT_BUTTON_LABEL}" onclick=\'open_popup("{$fields.position_name.module}", 600, 400, "", true, false, {literal}{"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"id":"position_id","name":"position_name"}}{/literal}, "single", true);\'><span class="suitepicon suitepicon-action-select"></span></button><button type="button" name="btn_clr_{$fields.position_name.name}" tabindex="0" title="{$APP.LBL_CLEAR_BUTTON_TITLE}" class="button lastChild" onclick="this.form.{$fields.position_name.name}.value = \'\'; this.form.{$fields.position_id.name}.value = \'\';" value="{$APP.LBL_CLEAR_BUTTON_LABEL}"><span class="suitepicon suitepicon-action-clear"></span></button></span>',
               ),
               array(
                  'name' => 'phone_work',
                  'label' => 'LBL_OFFICE_PHONE',
               ),
            ),
            array(
               array(
                  'name' => 'securitygroup_name',
                  'customCode' => '<input type="text" name="{$fields.securitygroup_name.name}" class="sqsEnabled" tabindex="0" id="{$fields.securitygroup_name.name}" size="" value="{$fields.securitygroup_name.value}" title="" autocomplete="off" >{$REPORTS_TO_JS}<input type="hidden" name="{$fields.securitygroup_id.name}" id="{$fields.securitygroup_id.name}" value="{$fields.securitygroup_id.value}"> <span class="id-ff multiple"><button type="button" name="btn_{$fields.securitygroup_name.name}" tabindex="0" title="{$APP.LBL_SELECT_BUTTON_TITLE}" class="button firstChild" value="{$APP.LBL_SELECT_BUTTON_LABEL}" onclick=\'open_popup("{$fields.securitygroup_name.module}", 600, 400, "&group_type_advanced[]=business_unit&group_type_advanced[]=department&group_type_advanced[]=team&group_type_advanced[]=other&group_type_advanced[]=standard", true, false, {literal}{"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"id":"securitygroup_id","name":"securitygroup_name"}}{/literal}, "single", true);\'><span class="suitepicon suitepicon-action-select"></span></button><button type="button" name="btn_clr_{$fields.securitygroup_name.name}" tabindex="0" title="{$APP.LBL_CLEAR_BUTTON_TITLE}" class="button lastChild" onclick="this.form.{$fields.securitygroup_name.name}.value = \'\'; this.form.{$fields.securitygroup_id.name}.value = \'\';" value="{$APP.LBL_CLEAR_BUTTON_LABEL}"><span class="suitepicon suitepicon-action-clear"></span></button></span>',
               ),
               'phone_mobile',
            ),
            array(
               array(
                  'name' => 'reports_to_name',
                  'label' => 'LBL_REPORTS_TO_NAME',
                  'customCode' => '<input type="text" name="{$fields.reports_to_name.name}" class="sqsEnabled" tabindex="0" id="{$fields.reports_to_name.name}" size="" value="{$fields.reports_to_name.value}" title="" autocomplete="off" >{$REPORTS_TO_JS}<input type="hidden" name="{$fields.reports_to_id.name}" id="{$fields.reports_to_id.name}" value="{$fields.reports_to_id.value}"> <span class="id-ff multiple"><button type="button" name="btn_{$fields.reports_to_name.name}" tabindex="0" title="{$APP.LBL_SELECT_BUTTON_TITLE}" class="button firstChild" value="{$APP.LBL_SELECT_BUTTON_LABEL}" onclick=\'open_popup("{$fields.reports_to_name.module}", 600, 400, "", true, false, {literal}{"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"id":"reports_to_id","name":"reports_to_name"}}{/literal}, "single", true);\'><span class="suitepicon suitepicon-action-select"></span></button><button type="button" name="btn_clr_{$fields.reports_to_name.name}" tabindex="0" title="{$APP.LBL_CLEAR_BUTTON_TITLE}" class="button lastChild" onclick="this.form.{$fields.reports_to_name.name}.value = \'\'; this.form.{$fields.reports_to_id.name}.value = \'\';" value="{$APP.LBL_CLEAR_BUTTON_LABEL}"><span class="suitepicon suitepicon-action-clear"></span></button></span>',
               ),
               'phone_other',
            ),
            array(
               'phone_home',
               array(
                  'name' => 'phone_fax',
                  'label' => 'LBL_FAX',
               ),
            ),
            array(
               'messenger_type',
               'messenger_id',
            ),
            array(
               array(
                  'name' => 'description',
                  'label' => 'LBL_NOTES',
               ),
            ),
            array(
               array(
                  'name' => 'address_street',
                  'type' => 'text',
                  'label' => 'LBL_PRIMARY_ADDRESS',
                  'displayParams' =>
                  array(
                     'rows' => 2,
                     'cols' => 30,
                  ),
               ),
               array(
                  'name' => 'address_city',
                  'label' => 'LBL_CITY',
               ),
            ),
            array(
               array(
                  'name' => 'address_state',
                  'label' => 'LBL_STATE',
               ),
               array(
                  'name' => 'address_postalcode',
                  'label' => 'LBL_POSTAL_CODE',
               ),
            ),
            array(
               array(
                  'name' => 'address_country',
                  'label' => 'LBL_COUNTRY',
               ),
            ),
            array(
               array(
                  'name' => 'email1',
                  'label' => 'LBL_EMAIL',
               ),
            ),
         ),
      ),
   ),
);
