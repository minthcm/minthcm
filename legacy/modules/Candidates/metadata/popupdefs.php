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

$popupMeta = array(
    'moduleMain' => 'Candidates',
    'varName' => 'Candidates',
    'orderBy' => 'candidates.first_name, candidates.last_name',
    'whereClauses' => array(
        'first_name' => 'candidates.first_name',
        'last_name' => 'candidates.last_name',
        'address_city' => 'candidates.address_city',
        'created_by_name' => 'candidates.created_by_name',
        'favorites_only' => 'candidates.favorites_only',
        'relocation' => 'candidates.relocation',
        'recr_contact_agree' => 'candidates.recr_contact_agree',
    ),
    'searchInputs' => array(
        'first_name',
        'last_name',
        'recr_contact_agree',
        'address_city',
        'created_by_name',
        'email',
        'favorites_only',
        'relocation',
    ),
    'searchdefs' => array(
        'first_name' => array(
            'name' => 'first_name',
        ),
        'last_name' => array(
            'name' => 'last_name',
        ),
        'created_by_name' => array(
            'name' => 'created_by_name',
        ),
        'recr_contact_agree' => array(
            'name' => 'recr_contact_agree',
            'label' => 'LBL_RECR_CONTACT_AGREE',
            'type' => 'bool',
        ),
        'relocation' => array(
            'type' => 'bool',
            'label' => 'LBL_RELOCATION',
            'name' => 'relocation',
        ),
        'email' => array(
            'name' => 'email',
        ),
        'favorites_only' => array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'label' => 'LBL_LIST_NAME',
            'link' => true,
            'default' => true,
            'related_fields' => array(
                'first_name',
                'last_name',
                'salutation',
            ),
        ),
        'ADDRESS_CITY' => array(
            'label' => 'LBL_PRIMARY_ADDRESS_CITY',
            'name' => 'address_city',
            'default' => true,
        ),
        'CREATED_BY_NAME' => array(
            'label' => 'LBL_CREATED',
            'name' => 'created_by_name',
            'default' => true,
        ),
        'email' => array(
            'name' => 'email',
            'width' => '10%',
        ),
        'RECR_CONTACT_AGREE' => array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_RECR_CONTACT_AGREE',
            'name' => 'recr_contact_agree',
        ),
        'RELOCATION' => array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_RELOCATION',
            'name' => 'relocation',
        ),
    ),
);
