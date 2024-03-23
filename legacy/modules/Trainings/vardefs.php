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
$dictionary['Trainings'] = array(
    'table' => 'trainings',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'date_start' => array(
            'required' => false,
            'name' => 'date_start',
            'vname' => 'LBL_DATE_START',
            'type' => 'datetimecombo',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
            'validation' => array('type' => 'isbefore', 'compareto' => 'date_end'),
        ),
        'date_end' => array(
            'required' => false,
            'name' => 'date_end',
            'vname' => 'LBL_DATE_END',
            'type' => 'datetimecombo',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
        ),
        'status' => array(
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => 'planned',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'training_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'training_type' => array(
            'required' => false,
            'name' => 'training_type',
            'vname' => 'LBL_TRAINING_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => 'external',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'training_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'meetings' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'trainings_meetings',
            'source' => 'non-db',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'vname' => 'LBL_MEETINGS',
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'trainings_documents',
            'source' => 'non-db',
            'module' => 'Documents',
            'bean_name' => 'Document',
            'vname' => 'LBL_DOCUMENTS',
        ),
        "parent_name" => array(
            'source' => 'non-db',
            'name' => 'parent_name',
            'vname' => 'LBL_PARENT_NAME',
            'type' => 'parent',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 25,
            'size' => '20',
            'options' => 'trainings_elastic_relations_list',
            'studio' => 'visible',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'parent_type' => 'record_type_display',
        ),
        "parent_type" => array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'dbType' => 'varchar',
            'studio' => 'hidden',
        ),
        "parent_id" => array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 36,
            'size' => '20',
        ),
        "certificates" => array(
            'name' => 'certificates',
            'type' => 'link',
            'relationship' => 'certificates_trainings',
            'source' => 'non-db',
            'module' => 'Certificates',
            'bean_name' => 'Certificates',
            'vname' => 'LBL_RELATIONSHIP_TRAININGS_NAME',
        ),
        "elements" => array(
            'name' => 'elements',
            'type' => 'link',
            'relationship' => 'onboardingoffboardingelements_trainings',
            'source' => 'non-db',
            'module' => 'OnboardingOffboardingElements',
            'bean_name' => 'OnboardingOffboardingElements',
            'vname' => 'LBL_RELATIONSHIP_ELEMENTS_NAME',
            'id_name' => 'element_id',
        ),
        "element_name" => array(
            'name' => 'element_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_RELATIONSHIP_ELEMENTS_NAME',
            'id_name' => 'element_id',
            'link' => 'elements',
            'module' => 'OnboardingOffboardingElements',
            'table' => 'onboardingoffboardingelements',
            'rname' => 'name',
        ),
        "element_id" => array(
            'name' => 'element_id',
            'relationship' => 'onboardingoffboardingelements_trainings',
            'type' => 'id',
            'vname' => 'LBL_RELATIONSHIP_ELEMENTS_ID',
        ),
        'files' => array(
            'name' => 'files',
            'type' => 'link',
            'relationship' => 'trainings_files',
            'source' => 'non-db',
            'module' => 'Files',
            'bean_name' => 'Files',
            'vname' => 'LBL_FILES',
            'label' => 'LBL_FILES',
        ),
        'requests' => array(
            'name' => 'requests',
            'type' => 'link',
            'relationship' => 'training_requests',
            'source' => 'non-db',
            'module' => 'Requests',
            'bean_name' => 'Requests',
            'vname' => 'LBL_REQUESTS',
            'label' => 'LBL_REQUESTS',
            'side' => 'right',
        ),
    ),
    'relationships' => array(
        "onboardingoffboardingelements_trainings" => array(
            'lhs_module' => 'OnboardingOffboardingElements',
            'lhs_table' => 'onboardingoffboardingelements',
            'lhs_key' => 'id',
            'rhs_module' => 'Trainings',
            'rhs_table' => 'trainings',
            'rhs_key' => 'element_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Trainings', 'Trainings',
    array(
        'basic',
        'assignable',
        'security_groups',
    )
);
