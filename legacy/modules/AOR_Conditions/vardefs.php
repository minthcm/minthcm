<?php
/**
 * Advanced OpenReports, SugarCRM Reporting.
 * @package Advanced OpenReports for SugarCRM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */

$dictionary['AOR_Condition'] = array(
    'table'=>'aor_conditions',
    'audited'=>false,
    'duplicate_merge'=>true,
    'fields'=>array(
  'aor_report_id' =>
  array(
    'required' => false,
    'name' => 'aor_report_id',
    'vname' => 'LBL_AOR_REPORT_ID',
    'type' => 'id',
    'massupdate' => 0,
    'comment' => 'The unique identifier of the Advanced Open Report (AOR) that this condition belongs to',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'condition_order' =>
  array(
    'required' => false,
    'name' => 'condition_order',
    'vname' => 'LBL_ORDER',
    'type' => 'int',
    'massupdate' => 0,
    'comment' => 'The sequential order in which this condition is evaluated within the report\'s filter criteria',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'enable_range_search' => false,
    'disable_num_format' => '',
  ),
        'logic_op' => array(
            'name' => 'logic_op',
            'vname' => 'LBL_LOGIC_OP',
            'type' => 'varchar',
            'default_value' => 'AND',
            'comment' => 'The logical operator used to combine this condition with other conditions in the report filter',
        ),
        'parenthesis' => array(
            'name' => 'parenthesis',
            'vname' => 'LBL_PARENTHESIS',
            'type' => 'varchar',
            'comment' => 'Parentheses used for grouping and nesting conditions to control evaluation precedence in complex filters',
        ),
  'module_path' =>
  array(
    'name' => 'module_path',
    'type' => 'longtext',
    'vname' => 'LBL_MODULE_PATH',
    'isnull' => true,
    'comment' => 'The module path or relationship chain that specifies which module and related records the field belongs to',
  ),
  'field' =>
  array(
    'required' => false,
    'name' => 'field',
    'vname' => 'LBL_FIELD',
    'type' => 'enum',
    'massupdate' => 0,
    'comment' => 'The specific field name from the selected module that this condition will evaluate',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'user_type_dom',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'operator' =>
  array(
    'required' => false,
    'name' => 'operator',
    'vname' => 'LBL_OPERATOR',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '',
    'comment' => 'The comparison operator used in the condition',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'aor_operator_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
    'value_type' =>
    array(
    'required' => false,
    'name' => 'value_type',
    'vname' => 'LBL_VALUE_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '',
    'comment' => 'The type of value being compared (static value, dynamic field reference, parameter, or function result)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'aor_condition_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'value' =>
  array(
    'required' => false,
    'name' => 'value',
    'vname' => 'LBL_VALUE',
    'type' => 'varchar',
    'massupdate' => 0,
    'comment' => 'The actual value, field reference, or expression that the selected field will be compared against',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
        'parameter' =>
            array(
                'required' => false,
                'name' => 'parameter',
                'vname' => 'LBL_PARAMETER',
                'type' => 'bool',
                'massupdate' => 0,
                'comment' => 'Additional parameters or options that modify how the condition is evaluated',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'studio' => 'visible',
            ),
  'aor_reports' =>
  array(
    'name' => 'aor_reports',
    'type' => 'link',
    'relationship' => 'aor_reports_aor_conditions',
    'module'=>'AOR_Reports',
    'bean_name'=>'AOR_Reports',
    'source'=>'non-db',
  ),
),
    'relationships'=>array(
),
    'indices' => array(
        array(
            'name' => 'aor_conditions_index_report_id',
            'type' => 'index',
            'fields' => array('aor_report_id'),
        ),
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);

if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('AOR_Conditions', 'AOR_Condition', array('basic'));

