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
$dictionary['Competencies'] = array(
    'table' => 'competencies',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'knowledge' => array(
            'name' => 'knowledge',
            'type' => 'link',
            'relationship' => 'knowledge_competencies',
            'source' => 'non-db',
            'module' => 'Knowledge',
            'bean_name' => 'Knowledge',
            'vname' => 'LBL_KNOWLEDGE',
        ),
        'skills' => array(
            'name' => 'skills',
            'type' => 'link',
            'relationship' => 'skills_competencies',
            'source' => 'non-db',
            'module' => 'Skills',
            'bean_name' => 'Skill',
            'vname' => 'LBL_SKILLS',
        ),
        'attitudes' => array(
            'name' => 'attitudes',
            'type' => 'link',
            'relationship' => 'attitudes_competencies',
            'source' => 'non-db',
            'module' => 'Attitudes',
            'bean_name' => 'Attitude',
            'vname' => 'LBL_ATTITUDES',
        ),
        'competencyratings' => array(
            'name' => 'competencyratings',
            'type' => 'link',
            'relationship' => 'competencyratings_competencies',
            'source' => 'non-db',
            'module' => 'CompetencyRatings',
            'bean_name' => 'CompetencyRatings',
            'side' => 'right',
            'vname' => 'LBL_COMPETENCYRATINGS',
        ),
        'appraisalitems' => array(
            'name' => 'appraisalitems',
            'type' => 'link',
            'relationship' => 'appraisalitems_competencies',
            'module' => 'AppraisalItems',
            'bean_name' => 'AppraisalItems',
            'source' => 'non-db',
            'vname' => 'LBL_APPRAISALITEMS',
        ),
        'competencies_type' => array(
            'id' => 'competencies_type',
            'name' => 'competencies_type',
            'vname' => 'LBL_COMPETENCIES_TYPE',
            'type' => 'enum',
            'options' => 'competencies_type_list',
            'audited' => true,
            'reportable' => true,
            'massupdate' => true,
            'duplicate_merge' => '0',
            'importable' => 'true',
        ),
    ),
    'relationships' => array(
        'appraisalitems_competencies' => array(
            'lhs_module' => 'Competencies',
            'lhs_table' => 'competencies',
            'lhs_key' => 'id',
            'rhs_module' => 'AppraisalItems',
            'rhs_table' => 'appraisalitems',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Competencies',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Competencies', 'Competencies', array('basic', 'assignable', 'security_groups', 'employee_related'));
