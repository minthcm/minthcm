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
$dictionary['AppraisalItems'] = array(
    'table' => 'appraisalitems',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'value' => array(
            'required' => false,
            'name' => 'value',
            'vname' => 'LBL_VALUE',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'rating_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'parent_name' => array(
            'required' => true,
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
            'options' => 'appraisal_subject_list',
            'studio' => 'visible',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'parent_type' => 'record_type_display',
        ),
        'parent_type' => array(
            'required' => true,
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
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'dbType' => 'varchar',
            'studio' => 'hidden',
        ),
        'parent_id' => array(
            'required' => true,
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
        'appraisal' => array(
            'name' => 'appraisal',
            'type' => 'link',
            'relationship' => 'appraisals_appraisalitems',
            'source' => 'non-db',
            'module' => 'Appraisals',
            'bean_name' => 'Appraisals',
            'vname' => 'LBL_APPRAISALS',
            'id_name' => 'appraisal_id',
        ),
        'appraisal_name' => array(
            'name' => 'appraisal_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_APPRAISAL_NAME',
            'save' => true,
            'id_name' => 'appraisal_id',
            'link' => 'appraisal',
            'table' => 'appraisals',
            'module' => 'Appraisals',
            'rname' => 'name',
            'required' => true,
        ),
        'appraisal_id' => array(
            'name' => 'appraisal_id',
            'type' => 'id',
            'relationship' => 'appraisals_appraisalitems',
            'vname' => 'LBL_APPRAISAL_ID',
        ),

        'competency' => array(
            'name' => 'competency',
            'type' => 'link',
            'relationship' => 'appraisalitems_competencies',
            'source' => 'non-db',
            'module' => 'Competencies',
            'bean_name' => 'Competencies',
            'vname' => 'LBL_COMPETENCY',
            'id_name' => 'competency_id',
        ),
        'competency_name' => array(
            'name' => 'competency_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_COMPETENCY_NAME',
            'save' => true,
            'id_name' => 'competency_id',
            'link' => 'competency',
            'table' => 'competencies',
            'module' => 'Competencies',
            'rname' => 'name',
            'required' => true,
        ),
        'competency_id' => array(
            'name' => 'competency_id',
            'type' => 'id',
            'relationship' => 'appraisalitems_competencies',
            'vname' => 'LBL_COMPETENCY_ID',
        ),
        'knowledge' => array(
            'name' => 'knowledge',
            'type' => 'link',
            'relationship' => 'appraisalitems_knowledge',
            'source' => 'non-db',
            'module' => 'Knowledge',
            'bean_name' => 'Knowledge',
            'vname' => 'LBL_KNOWLEDGE',
            'id_name' => 'knowledge_id',
        ),
        'knowledge_name' => array(
            'name' => 'knowledge_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_KNOWLEDGE_NAME',
            'save' => true,
            'id_name' => 'knowledge_id',
            'link' => 'knowledge',
            'table' => 'knowledge',
            'module' => 'Knowledge',
            'rname' => 'name',
            'required' => true,
        ),
        'knowledge_id' => array(
            'name' => 'knowledge_id',
            'type' => 'id',
            'relationship' => 'appraisalitems_knowledge',
            'vname' => 'LBL_KNOWLEDGE_ID',
        ),
        'skill' => array(
            'name' => 'skill',
            'type' => 'link',
            'relationship' => 'appraisalitems_skills',
            'source' => 'non-db',
            'module' => 'Skills',
            'bean_name' => 'Skills',
            'vname' => 'LBL_SKILLS',
            'id_name' => 'skill_id',
        ),
        'skill_name' => array(
            'name' => 'skill_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_SKILL_NAME',
            'save' => true,
            'id_name' => 'skill_id',
            'link' => 'skill',
            'table' => 'skills',
            'module' => 'Skills',
            'rname' => 'name',
            'required' => true,
        ),
        'skill_id' => array(
            'name' => 'skill_id',
            'type' => 'id',
            'relationship' => 'appraisalitems_skills',
            'vname' => 'LBL_SKILL_ID',
        ),
        'attitude' => array(
            'name' => 'attitude',
            'type' => 'link',
            'relationship' => 'appraisalitems_attitudes',
            'source' => 'non-db',
            'module' => 'Attitudes',
            'bean_name' => 'Attitudes',
            'vname' => 'LBL_ATTITUDES',
            'id_name' => 'attitude_id',
        ),
        'attitude_name' => array(
            'name' => 'attitude_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_ATTITUDES_NAME',
            'save' => true,
            'id_name' => 'attitude_id',
            'link' => 'attitude',
            'table' => 'attitudes',
            'module' => 'Attitudes',
            'rname' => 'name',
            'required' => true,
        ),
        'attitude_id' => array(
            'name' => 'attitude_id',
            'type' => 'id',
            'relationship' => 'appraisalitems_attitudes',
            'vname' => 'LBL_ATTITUDES_ID',
        ),
        'responsibiliti' => array(
            'name' => 'responsibiliti',
            'type' => 'link',
            'relationship' => 'responsibilities_positions',
            'source' => 'non-db',
            'module' => 'Responsibilities',
            'bean_name' => 'Responsibilities',
            'vname' => 'LBL_RESPONSIBILITIES',
            'id_name' => 'responsibiliti_id',
        ),
        'responsibiliti_name' => array(
            'name' => 'responsibiliti_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_RESPONSIBILITI_NAME',
            'save' => true,
            'id_name' => 'responsibiliti_id',
            'link' => 'responsibiliti',
            'table' => 'responsibilities',
            'module' => 'Responsibilities',
            'rname' => 'name',
            'required' => true,
        ),
        'responsibiliti_id' => array(
            'name' => 'responsibiliti_id',
            'type' => 'id',
            'relationship' => 'responsibilities_positions',
            'vname' => 'LBL_RESPONSIBILITI_ID',
        ),
        'goal' => array(
            'name' => 'goal',
            'type' => 'link',
            'relationship' => 'appraisalitems_goals',
            'source' => 'non-db',
            'module' => 'Goals',
            'bean_name' => 'Goals',
            'vname' => 'LBL_GOALS',
            'id_name' => 'goal_id',
        ),
        'goal_name' => array(
            'name' => 'goal_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_GOAL_NAME',
            'save' => true,
            'id_name' => 'goal_id',
            'link' => 'goal',
            'table' => 'goals',
            'module' => 'Goals',
            'rname' => 'name',
            'required' => true,
        ),
        'goal_id' => array(
            'name' => 'goal_id',
            'type' => 'id',
            'relationship' => 'appraisalitems_goals',
            'vname' => 'LBL_GOAL_ID',
        ),
        'responsibilityactivitie' => array(
            'name' => 'responsibilityactivitie',
            'type' => 'link',
            'relationship' => 'appraisalitems_responsibilityactivities',
            'source' => 'non-db',
            'module' => 'ResponsibilityActivities',
            'bean_name' => 'ResponsibilityActivities',
            'vname' => 'LBL_RESPONSIBILITYACTIVITIES',
            'id_name' => 'responsibilityactivitie_id',
        ),
        'responsibilityactivitie_name' => array(
            'name' => 'responsibilityactivitie_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_RESPONSIBILITYACTIVITIE_NAME',
            'save' => true,
            'id_name' => 'responsibilityactivitie_id',
            'link' => 'responsibilityactivitie',
            'table' => 'responsibilityactivities',
            'module' => 'ResponsibilityActivities',
            'rname' => 'name',
            'required' => true,
        ),
        'responsibilityactivitie_id' => array(
            'name' => 'responsibilityactivitie_id',
            'type' => 'id',
            'relationship' => 'appraisalitems_responsibilityactivities',
            'vname' => 'LBL_RESPONSIBILITYACTIVITIE_ID',
        ),
    ),
    'relationships' => array(
        "appraisals_appraisalitems" => array(
            'lhs_module' => 'Appraisals',
            'lhs_table' => 'appraisals',
            'lhs_key' => 'id',
            'rhs_module' => 'AppraisalItems',
            'rhs_table' => 'appraisalitems',
            'rhs_key' => 'appraisal_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('AppraisalItems', 'AppraisalItems', array('basic', 'assignable', 'security_groups'));

$dictionary['AppraisalItems']['fields']['name']['vt_calculated'] = 'concat($appraisal_name, " - ", $parent_name)';
