<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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
global $dictionary;
if (empty($dictionary['User'])) {
    include 'modules/Users/vardefs.php';
}
$dictionary['Employee'] = $dictionary['User'];
//users of employees modules are not allowed to change the employee/user status.
$dictionary['Employee']['fields']['status']['massupdate'] = false;
$dictionary['Employee']['fields']['is_admin']['massupdate'] = false;
//begin bug 48033
$dictionary['Employee']['fields']['UserType']['massupdate'] = false;
$dictionary['Employee']['fields']['messenger_type']['massupdate'] = false;
$dictionary['Employee']['fields']['email_link_type']['massupdate'] = false;
//end bug 48033
$dictionary['Employee']['fields']['email1']['required'] = false;
$dictionary['Employee']['fields']['email_addresses']['required'] = false;
$dictionary['Employee']['fields']['email_addresses_primary']['required'] = false;
// bugs 47553 & 49716
$dictionary['Employee']['fields']['status']['studio'] = false;
$dictionary['Employee']['fields']['status']['required'] = false;

$dictionary["Employee"]["fields"]["employeecertificates"] = array(
    'name' => 'employeecertificates',
    'type' => 'link',
    'relationship' => 'employeecertificates_employees',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_EMPLOYEECERTIFICATES',
);
$dictionary["Employee"]["fields"]["spenttime"] = array(
    'name' => 'spenttime',
    'type' => 'link',
    'relationship' => 'spenttime_employees',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_USERS_SPENT_TIME_TITLE',
);
$dictionary["Employee"]["fields"]["contracts"] = array(
    'name' => 'contracts',
    'type' => 'link',
    'relationship' => 'contracts_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_CONTRACTS',
);
$dictionary["Employee"]["fields"]["resources"] = array(
    'name' => 'resources',
    'type' => 'link',
    'relationship' => 'resources_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_RESOURCES',
);
$dictionary["Employee"]["fields"]["reservations"] = array(
    'name' => 'reservations',
    'type' => 'link',
    'relationship' => 'reservations_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_RESERVATIONS',
);
$dictionary["Employee"]["fields"]["periodsofemployment"] = array(
    'name' => 'periodsofemployment',
    'type' => 'link',
    'relationship' => 'periodsofemployment_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_PERIODSOFEMPLOYMENT',
);

$dictionary["Employee"]["fields"]["goals"] = array(
    'name' => 'goals',
    'type' => 'link',
    'relationship' => 'goals_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_GOALS',
);
$dictionary["Employee"]["fields"]["appraisals"] = array(
    'name' => 'appraisals',
    'type' => 'link',
    'relationship' => 'appraisals_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_APPRAISALS',
);
$dictionary["Employee"]["fields"]["evaluations"] = array(
    'name' => 'evaluations',
    'type' => 'link',
    'relationship' => 'appraisals_employees_evaluations',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_EVALUATIONS',
);
$dictionary["Employee"]["fields"]["roles"] = array(
    'name' => 'roles',
    'type' => 'link',
    'relationship' => 'roles_employees',
    'source' => 'non-db',
    'module' => 'EmployeeRoles',
    'bean_name' => 'EmployeeRoles',
    'vname' => 'LBL_ROLES',
);
$dictionary["Employee"]["fields"]["benefits"] = array(
    'name' => 'benefits',
    'type' => 'link',
    'relationship' => 'benefits_employees',
    'source' => 'non-db',
    'module' => 'Benefits',
    'bean_name' => 'Benefits',
    'vname' => 'LBL_RESPONSIBILITIES',
);
$dictionary["Employee"]["fields"]["onboardings"] = array(
    'name' => 'onboardings',
    'type' => 'link',
    'relationship' => 'onboardings_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_ONBOARDINGS',
);
$dictionary["Employee"]["fields"]["offboardings"] = array(
    'name' => 'offboardings',
    'type' => 'link',
    'relationship' => 'offboardings_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_OFFBOARDINGS',
);
$dictionary["Employee"]["fields"]["competencyratings"] = array(
    'name' => 'competencyratings',
    'type' => 'link',
    'relationship' => 'competencyratings_employee',
    'module' => 'CompetencyRatings',
    'bean_name' => 'CompetencyRatings',
    'source' => 'non-db',
    'vname' => 'LBL_COMPETENCYRATINGS',
);
$dictionary["Employee"]["fields"]["securitygroups_managers"] = array(
    'name' => 'securitygroups_managers',
    'type' => 'link',
    'relationship' => 'employees_securitygroups',
    'source' => 'non-db',
    'module' => 'SecurityGroups',
    'bean_name' => 'SecurityGroup',
    'vname' => 'LBL_SECURITYGROUPS_MANAGERS',
    'side' => 'right',
);

$dictionary["Employee"]["fields"]["securitygroups_employees"] = array(
    'name' => 'securitygroups_employees',
    'type' => 'link',
    'relationship' => 'securitygroups_employees',
    'source' => 'non-db',
    'module' => 'SecurityGroups',
    'bean_name' => 'SecurityGroup',
    'vname' => 'LBL_SECURITYGROUPS_EMPLOYEES',
    'id_name' => 'securitygroup_id',
);
$dictionary["Employee"]["fields"]["securitygroup_name"] = array(
    'name' => 'securitygroup_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_SECURITYGROUP_NAME',
    'save' => true,
    'id_name' => 'securitygroup_id',
    'link' => 'securitygroups_employees',
    'module' => 'SecurityGroups',
    'table' => 'securitygroups',
    'rname' => 'name',
);
$dictionary["Employee"]["fields"]["securitygroup_id"] = array(
    'name' => 'securitygroup_id',
    'relationship' => 'securitygroups_employees',
    'type' => 'link',
    'vname' => 'LBL_SECURITYGROUP_ID',
    'dbType' => 'id',
    'join_name' => 'securitygroups_employees',
);
$dictionary["Employee"]["fields"]["certificates"] = array(
    'name' => 'certificates',
    'type' => 'link',
    'relationship' => 'certificates_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_CERTIFICATES',
);
$dictionary["Employee"]["fields"]["applications"] = array(
    'name' => 'applications',
    'type' => 'link',
    'relationship' => 'applications_employee',
    'source' => 'non-db',
    'side' => 'right',
    'vname' => 'LBL_APPLICATIONS_SUBPANEL',
);

$dictionary["Employee"]["fields"]["prospect_lists"] = array(
    'name' => 'prospect_lists',
    'type' => 'link',
    'relationship' => 'prospect_list_employees',
    'module' => 'ProspectLists',
    'source' => 'non-db',
    'vname' => 'LBL_PROSPECT_LIST',
);
$dictionary["Employee"]["fields"]["allocations_employees"] = array(
    'name' => "allocations_employees",
    'type' => 'link',
    'relationship' => "allocations_employees",
    'source' => 'non-db',
    'module' => 'Allocations',
    'bean_name' => 'Allocations',
    'vname' => 'LBL_LINKED_ALLOCATIONS_TITLE',
);
$dictionary["Employee"]["fields"]["trainings"] = array(
    'name' => 'trainings',
    'type' => 'link',
    'relationship' => 'trainings_assigned_user',
    'source' => 'non-db',
    'module' => 'Trainings',
    'bean_name' => 'Trainings',
    'vname' => 'LBL_TRAININGS',
);
$dictionary["Employee"]["fields"]["tasks"] = array(
    'name' => 'tasks',
    'type' => 'link',
    'relationship' => 'tasks_assigned_user',
    'source' => 'non-db',
    'module' => 'Tasks',
    'bean_name' => 'Tasks',
    'vname' => 'LBL_TASKS',
);
$dictionary['Employee']['fields']['candidatures'] = array(
    'name' => 'candidatures',
    'type' => 'link',
    'relationship' => 'employee_candidatures',
    'source' => 'non-db',
    'module' => 'Candidatures',
    'bean_name' => 'Candidatures',
    'vname' => 'LBL_CANDIDATURES',
    'label' => 'LBL_CANDIDATURES',
);
$dictionary['Employee']['fields']['candidatures'] = array(
    'name' => 'candidatures',
    'type' => 'link',
    'relationship' => 'employee_candidatures',
    'source' => 'non-db',
    'module' => 'Candidatures',
    'bean_name' => 'Candidatures',
    'vname' => 'LBL_CANDIDATURES',
    'label' => 'LBL_CANDIDATURES',
);
$dictionary['Employee']['fields']['deputy'] = array(
    'name' => 'deputy',
    'type' => 'link',
    'relationship' => 'deputy_workschedules',
    'source' => 'non-db',
    'side' => 'left',
    'vname' => 'LBL_DEPUTY',
);

$dictionary["Employee"]["audited"] = true;
$dictionary["Employee"]["fields"]["employee_status"]["audited"] = true;
$dictionary["Employee"]["fields"]["first_name"]["audited"] = true;
$dictionary["Employee"]["fields"]["last_name"]["audited"] = true;
$dictionary["Employee"]["fields"]["position_name"]["audited"] = true;
$dictionary["Employee"]["fields"]["position_name"]["required"] = true;
$dictionary["Employee"]["fields"]["phone_work"]["audited"] = true;
$dictionary["Employee"]["fields"]["phone_mobile"]["audited"] = true;
$dictionary["Employee"]["fields"]["phone_home"]["audited"] = true;
$dictionary["Employee"]["fields"]["phone_fax"]["audited"] = true;
$dictionary["Employee"]["fields"]["phone_other"]["audited"] = true;
$dictionary["Employee"]["fields"]["securitygroup_name"]["audited"] = true;
$dictionary["Employee"]["fields"]["reports_to_name"]["audited"] = true;
$dictionary["Employee"]["fields"]["messenger_type"]["audited"] = true;
$dictionary["Employee"]["fields"]["messenger_id"]["audited"] = true;
$dictionary["Employee"]["fields"]["address_street"]["audited"] = true;
$dictionary["Employee"]["fields"]["address_city"]["audited"] = true;
$dictionary["Employee"]["fields"]["address_state"]["audited"] = true;
$dictionary["Employee"]["fields"]["address_country"]["audited"] = true;
$dictionary["Employee"]["fields"]["address_postalcode"]["audited"] = true;
$dictionary["Employee"]["fields"]["business_role"]["audited"] = false;
$dictionary["Employee"]["fields"]["candidate_id"]["audited"] = false;
