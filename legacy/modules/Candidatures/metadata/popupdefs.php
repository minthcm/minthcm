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
    'moduleMain' => 'Candidatures',
    'varName' => 'Candidatures',
    'orderBy' => 'candidatures.name',
    'whereClauses' => array(
        'name' => 'candidatures.name',
        'parent_name' => 'candidatures.parent_name',
        'status' => 'candidatures.status',
        'source' => 'candidatures.source',
        'assigned_user_id' => 'candidatures.assigned_user_id',
        'favorites_only' => 'candidatures.favorites_only',
    ),
    'searchInputs' => array(
        'name',
        'parent_name',
        'status',
        'source',
        'assigned_user_id',
        'favorites_only',
        'reason_for_rejection',
    ),
    'searchdefs' => array(
        'name' => array(
            'name' => 'name',
        ),
        'parent_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_CANDIDATES_TITLE',
            'id' => 'CANDIDATES_CANDIDATURESCANDIDATES_IDA',
            'sortable' => false,
            'name' => 'parent_name',
        ),
        'status' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'name' => 'status',
        ),
        'source' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE',
            'name' => 'source',
        ),
        'reason_for_rejection' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_REASON_FOR_REJECTION',
            'name' => 'reason_for_rejection',
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array(
                'name' => 'get_user_array',
                'params' => array(
                    false,
                ),
            ),
        ),
        'favorites_only' => array(
            'name' => 'favorites_only',
            'label' => 'LBL_FAVORITES_FILTER',
            'type' => 'bool',
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ),
        'STATUS' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'name' => 'status',
        ),
        'TO_DECISION' => array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_TO_DECISION',
        ),
        'CANDIDATES_NAME' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_CANDIDATES_TITLE',
            'id' => 'CANDIDATES_CANDIDATURESCANDIDATES_IDA',
            'sortable' => false,
            'default' => true,
            'name' => 'parent_name',
        ),
        'NET_AMOUNT' => array(
            'related_fields' => array(
                'currency_id',
            ),
            'type' => 'currency',
            'default' => true,
            'label' => 'LBL_NET_AMOUNT',
            'currency_format' => true,
        ),
        'GROSS_AMOUNT' => array(
            'related_fields' => array(
                'currency_id',
            ),
            'type' => 'currency',
            'default' => true,
            'label' => 'LBL_GROSS_AMOUNT',
            'currency_format' => true,
        ),
        'DG_AMOUNT' => array(
            'related_fields' => array(
                'currency_id',
            ),
            'type' => 'currency',
            'default' => true,
            'label' => 'LBL_DG_AMOUNT',
            'currency_format' => true,
        ),
        'SCORING' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'SCORING',
        ),
        'ASSIGNED_USER_NAME' => array(
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'name' => 'assigned_user_name',
        ),
        'EMPLOYEE_NAME' => array(
            'width' => '9%',
            'label' => 'LBL_EMPLOYEE_NAME',
            'module' => 'Employees',
            'id' => 'EMPLOYEE_ID',
            'default' => true,
        ),

        'DATE_MODIFIED' => array(
            'type' => 'datetime',
            'studio' => array(
                'portaleditview' => false,
            ),
            'readonly' => true,
            'label' => 'LBL_DATE_MODIFIED',
            'default' => true,
        ),
    ),
);
