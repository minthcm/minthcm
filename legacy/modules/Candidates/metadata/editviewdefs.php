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

$module_name = 'Candidates';
$viewdefs[$module_name] = array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '2',
            'useTabs' => false,
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
            'tabDefs' => array(
                'LBL_CONTACT_INFORMATION' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_SHOW_MORE_INFORMATION' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_RECORDVIEW_PANEL1' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_RECORDVIEW_PANEL2' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'lbl_contact_information' => array(
                array(
                    array(
                        'name' => 'first_name',
                        'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
                    ),
                    'last_name',
                ),
                array(
                    array(
                        'name' => 'phone_mobile',
                        'comment' => 'Mobile phone number of the contact',
                        'label' => 'LBL_MOBILE_PHONE',
                    ),
                    array(
                        'name' => 'recr_contact_agree',
                        'label' => 'LBL_RECR_CONTACT_AGREE',
                    ),
                ),
                array(
                    array(
                        'name' => 'email1',
                        'label' => 'LBL_EMAIL',
                    ),
                ),
                array(
                    array(
                        'name' => 'primary_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' => array(
                            'key' => 'primary',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                    ),
                    array(
                        'name' => 'alt_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' => array(
                            'key' => 'alt',
                            'copy' => 'primary',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                    ),
                ),
                array(
                    'birthdate',
                    '',
                ),
            ),
            'LBL_SHOW_MORE_INFORMATION' => array(
                array(
                    'potential',
                    'relocation',
                ),
                array(
                    'last_time_contact',
                    'date_planned_contact',
                ),
                array(
                    'description',
                ),
            ),
            'LBL_RECORDVIEW_PANEL1' => array(
                array(
                    'linkedin',
                    'goldenline',
                ),
                array(
                    'facebook',
                    'skype',
                ),
            ),
            'LBL_RECORDVIEW_PANEL2' => array(
                array(
                    'assigned_user_name',
                    '',
                ),
            ),
        ),
    ),
);
