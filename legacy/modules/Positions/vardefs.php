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

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$dictionary['Positions'] = array(
    'table' => 'positions',
    'audited' => true,
    'activity_enabled' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'required' => false,
            'massupdate' => true,
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'position_status',
            'default' => 'active',
        ),
        "recruitments" => array(
            'name' => 'recruitments',
            'type' => 'link',
            'relationship' => 'recruitments_positions',
            'source' => 'non-db',
            'module' => 'Recruitments',
            'bean_name' => 'Recruitments',
            'vname' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
            'id_name' => 'position_id',
            'link-type' => 'many',
            'side' => 'left',
        ),
        'securitygroups_leader' => array(
            'name' => 'securitygroups_leader',
            'type' => 'link',
            'relationship' => 'securitygroups_positions_leader',
            'source' => 'non-db',
            'module' => 'SecurityGroups',
            'bean_name' => 'SecurityGroup',
            'vname' => 'LBL_SECURITYGROUPS_POSITIONS_LEADER_TITLE',
            'id_name' => 'securitygroup_leader_id',
        ),
        'securitygroup_leader_name' => array(
            'name' => 'securitygroup_leader_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_SECURITYGROUPS_LEADER_NAME',
            'save' => true,
            'id_name' => 'securitygroup_leader_id',
            'link' => 'securitygroups_leader',
            'table' => 'securitygroups',
            'module' => 'SecurityGroups',
            'rname' => 'name',
        ),
        'securitygroup_leader_id' => array(
            'name' => 'securitygroup_leader_id',
            'type' => 'link',
            'relationship' => 'securitygroups_positions_leader',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_SECURITYGROUPS_LEADER_ID',
            'audited' => true,
        ),
        'positions_supervision' => array(
            'name' => 'positions_supervision',
            'type' => 'link',
            'relationship' => 'positions_supervision',
            'source' => 'non-db',
            'module' => 'Positions',
            'bean_name' => 'Positions',
            'vname' => 'LBL_POSITIONS_POSITIONS_SUPERVISION',
            'id_name' => 'positions_supervision_id',
            'link_type' => 'one',
            'side' => 'right',
        ),
        'positions_supervision_name' => array(
            'name' => 'positions_supervision_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_POSITIONS_SUPERVISION_NAME',
            'save' => true,
            'id_name' => 'positions_supervision_id',
            'link' => 'positions_supervision',
            'module' => 'Positions',
            'table' => 'positions',
            'rname' => 'name',
            'vt_validation' => "AEM(callCustomApi('Positions', 'checkSubordinatedPositions', {id:\$id, positions_supervision_id:\$positions_supervision_id}), 'LBL_PARENT_SUPERVISION_ERROR')",
        ),
        'positions_supervision_id' => array(
            'name' => 'positions_supervision_id',
            'relationship' => 'positions_supervision',
            'type' => 'id',
            'vname' => 'LBL_POSITIONS_SUPERVISION_ID',
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
        ),
        'positions_supervision_left' => array(
            'name' => 'positions_supervision_left',
            'type' => 'link',
            'relationship' => 'positions_supervision',
            'source' => 'non-db',
            'module' => 'Positions',
            'bean_name' => 'Positions',
            'vname' => 'LBL_POSITIONS_POSITIONS_SUPERVISION_LEFT',
            'id_name' => 'positions_supervision_id',
            'link_type' => 'many',
            'side' => 'left',
            'reportable' => false,
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'positions_documents',
            'source' => 'non-db',
            'module' => 'Documents',
            'bean_name' => 'Documents',
            'vname' => 'LBL_DOCUMENTS',
        ),
        'employees' => array(
            'name' => 'employees',
            'type' => 'link',
            'relationship' => 'positions_employees',
            'source' => 'non-db',
            'module' => 'Employees',
            'bean_name' => 'Employee',
            'side' => 'right',
            'vname' => 'LBL_POSITIONS_EMPLOYEES',
        ),
        'onboardingtemplates' => array(
            'name' => 'onboardingtemplates',
            'type' => 'link',
            'relationship' => 'onboardingtemplates_positions',
            'source' => 'non-db',
            'module' => 'OnboardingTemplates',
            'bean_name' => 'OnboardingTemplates',
            'vname' => 'LBL_ONBOARDINGTEMPLATES_POSITIONS',
            'id_name' => 'onboardingtemplate_id',
        ),
        'onboardingtemplate_name' => array(
            'name' => 'onboardingtemplate_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_ONBOARDINGTEMPLATE_NAME',
            'save' => true,
            'id_name' => 'onboardingtemplate_id',
            'link' => 'onboardingtemplates',
            'module' => 'OnboardingTemplates',
            'table' => 'onboardingtemplates',
            'rname' => 'name',
        ),
        'onboardingtemplate_id' => array(
            'name' => 'onboardingtemplate_id',
            'relationship' => 'onboardingtemplates_positions',
            'type' => 'id',
            'vname' => 'LBL_ONBOARDINGTEMPLATE_ID',
            'rname' => 'id',
            'dbType' => 'id',
        ),
        'offboardingtemplates' => array(
            'name' => 'offboardingtemplates',
            'type' => 'link',
            'relationship' => 'offboardingtemplates_positions',
            'source' => 'non-db',
            'module' => 'OffboardingTemplates',
            'bean_name' => 'OffboardingTemplates',
            'vname' => 'LBL_OFFBOARDINGTEMPLATES_POSITIONS',
            'id_name' => 'offboardingtemplate_id',
        ),
        'offboardingtemplate_name' => array(
            'name' => 'offboardingtemplate_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_OFFBOARDINGTEMPLATE_NAME',
            'save' => true,
            'id_name' => 'offboardingtemplate_id',
            'link' => 'offboardingtemplates',
            'module' => 'OffboardingTemplates',
            'table' => 'offboardingtemplates',
            'rname' => 'name',
        ),
        'offboardingtemplate_id' => array(
            'name' => 'offboardingtemplate_id',
            'relationship' => 'offboardingtemplates_positions',
            'type' => 'id',
            'vname' => 'LBL_OFFBOARDINGTEMPLATE_ID',
            'rname' => 'id',
            'dbType' => 'id',
        ),
        'benefits' => array(
            'name' => 'benefits',
            'type' => 'link',
            'relationship' => 'benefits_positions',
            'source' => 'non-db',
            'module' => 'Benefits',
            'bean_name' => 'Benefits',
            'vname' => 'LBL_BENEFITS_LINK',
        ),
        'responsibilities' => array(
            'name' => 'responsibilities',
            'type' => 'link',
            'relationship' => 'responsibilities_positions',
            'source' => 'non-db',
            'module' => 'Responsibilities',
            'bean_name' => 'Responsibilities',
            'vname' => 'LBL_RESPONSIBILITIES_LINK',
        ),
        'competencyratings' => array(
            'name' => 'competencyratings',
            'type' => 'link',
            'relationship' => 'competencyratings_positions',
            'module' => 'CompetencyRatings',
            'bean_name' => 'CompetencyRatings',
            'source' => 'non-db',
            'vname' => 'LBL_COMPETENCYRATINGS_POSITIONS_TITLE',
        ),
        'appraisals' => array(
            'name' => 'appraisals',
            'type' => 'link',
            'relationship' => 'positions_appraisals',
            'source' => 'non-db',
            'module' => 'Appraisals',
            'bean_name' => 'Appraisals',
            'side' => 'right',
            'vname' => 'LBL_POSITIONS_APPRAISALS',
        ),
        'careerpaths_from' => array(
            'name' => 'careerpaths_from',
            'type' => 'link',
            'relationship' => 'careerpaths_positions_from',
            'source' => 'non-db',
            'module' => 'CareerPaths',
            'bean_name' => 'CareerPaths',
            'side' => 'right',
            'vname' => 'LBL_POSITIONS_CAREERPATHS_FROM_TITLE',
        ),
        'careerpaths_to' => array(
            'name' => 'careerpaths_to',
            'type' => 'link',
            'relationship' => 'careerpaths_positions_to',
            'source' => 'non-db',
            'module' => 'CareerPaths',
            'bean_name' => 'CareerPaths',
            'side' => 'right',
            'vname' => 'LBL_POSITIONS_CAREERPATHS_TO_TITLE',
        ),
        'securitygroups_membership' => array(
            'name' => 'securitygroups_membership',
            'type' => 'link',
            'relationship' => 'securitygroups_positions_membership',
            'source' => 'non-db',
            'module' => 'SecurityGroups',
            'bean_name' => 'SecurityGroup',
            'vname' => 'LBL_SECURITYGROUPS_POSITIONS_MEMBERSHIP',
        ),
        'salaryranges' => array(
            'name' => 'salaryranges',
            'type' => 'link',
            'relationship' => 'position_salaryranges',
            'source' => 'non-db',
            'module' => 'SalaryRanges',
            'bean_name' => 'SalaryRanges',
            'side' => 'right',
            'vname' => 'LBL_SALARYRANGES',
        ),
        'termsofemployment' => array(
            'name' => 'termsofemployment',
            'type' => 'link',
            'relationship' => 'positions_termsofemployment',
            'source' => 'non-db',
            'module' => 'TermsOfEmployment',
            'bean_name' => 'TermsOfEmployment',
            'side' => 'right',
            'vname' => 'LBL_TERMSOFEMPLOYMENT',
        ),
        'files' => array(
            'name' => 'files',
            'type' => 'link',
            'relationship' => 'positions_files',
            'source' => 'non-db',
            'module' => 'Files',
            'bean_name' => 'Files',
            'vname' => 'LBL_FILES',
            'label' => 'LBL_FILES',
        ),
    ),
    'relationships' => array(
        'onboardingtemplates_positions' => array(
            'lhs_module' => 'OnboardingTemplates',
            'lhs_table' => 'onboardingtemplates',
            'lhs_key' => 'id',
            'rhs_module' => 'Positions',
            'rhs_table' => 'positions',
            'rhs_key' => 'onboardingtemplate_id',
            'relationship_type' => 'one-to-many',
        ),
        'offboardingtemplates_positions' => array(
            'lhs_module' => 'OffboardingTemplates',
            'lhs_table' => 'offboardingtemplates',
            'lhs_key' => 'id',
            'rhs_module' => 'Positions',
            'rhs_table' => 'positions',
            'rhs_key' => 'offboardingtemplate_id',
            'relationship_type' => 'one-to-many',
        ),
        'recruitments_positions' => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'Recruitments',
            'rhs_table' => 'recruitments',
            'rhs_key' => 'position_id',
            'relationship_type' => 'one-to-many',
        ),
        'positions_supervision' => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'Positions',
            'rhs_table' => 'positions',
            'rhs_key' => 'positions_supervision_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Positions', 'Positions', array('basic', 'assignable', 'security_groups'));

$dictionary['Positions']['fields']['name']['audited'] = true;
