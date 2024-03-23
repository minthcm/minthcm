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

$module_name = 'ScheduleReports';
$viewdefs [$module_name]['emailoptionsView'] = array(
   'templateMeta' =>
   array(
      'form' =>
      array(
         'headerTpl' => 'include/EditView/header.tpl',
         'footerTpl' => 'include/EditView/footer.tpl',
         'buttons' =>
         array(
            array(
               'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" onclick="disableOnUnloadEditView(this.form);this.form.action.value=\'saveEmailOptions\'; this.form.module.value=\'ScheduleReports\';" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
            ),
            array(
               'customCode' => '<input title="' . $GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'] . ' [Alt+X]" accessKey="X" onclick="this.form.action.value=\'index\'; this.form.module.value=\'ScheduleReports\';" type="submit" name="button" value="' . $GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'] . '">',
            ),
         ),
      ),
      'maxColumns' => '1',
      'widths' =>
      array(
         array(
            'label' => '10',
            'field' => '90',
         ),
      ),
      'includes' => array(
         array(
            'file' => 'modules/ScheduleReports/js/email_options.js'
         ),
      ),
   ),
   'panels' => array(
      'default' => array(
         array(
            array(
               'label' => "LBL_DEFAULT_TEMPLATE",
               'customCode' => ' <slot>
									        		<select id="email_template_id" name="email_template_id" {$IE_DISABLED}>{$TMPL_DRPDWN_GENERATE}</select>
													<input type="button" class="button" onclick="javascript:window.email_options.open_email_template_form(\'email_template_id\')" value="{$MOD.LBL_CREATE_TEMPLATE}" {$IE_DISABLED}>
													<input type="button" value="{$MOD.LBL_EDIT_TEMPLATE}" class="button" onclick="javascript:window.email_options.edit_email_template_form(\'email_template_id\')" name="edit_email_template_id" id="edit_email_template_id" style="{$EDIT_TEMPLATE}">
												</slot>',
            ),
         ),
      ),
   ),
);


