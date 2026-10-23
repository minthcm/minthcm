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
 * Copyright (C) 2018-2024 MintHCM
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
#[\AllowDynamicProperties]
class AOR_Condition extends Basic
{
    public $new_schema = true;
    public $module_dir = 'AOR_Conditions';
    public $object_name = 'AOR_Condition';
    public $table_name = 'aor_conditions';
    public $tracker_visibility = false;
    public $importable = true;
    public $disable_row_level_security = true;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $aor_report_id;
    public $condition_order;
    public $field;
    public $logic_op;
    public $parenthesis;
    public $operator;
    public $value;
    public $value_type;

    public function __construct()
    {
        parent::__construct();
    }




    public function save_lines($post_data, $parent, $key = '')
    {
        require_once('modules/AOW_WorkFlow/aow_utils.php');

        // report_module doesn't change between lines - resolve the allowlist once, not per row.
        $report_module_bean = BeanFactory::newBean($parent->report_module);
        $allowed_fields = $report_module_bean ? array_keys($report_module_bean->field_defs) : [];

        $postData = null;
        if (isset($post_data[$key . 'field'])) {
            $postData = $post_data[$key . 'field'];
        } else {
            LoggerManager::getLogger()->warn('Field in POST data is not set for save lines at key: ' . $key);
        }

        $j = 0;
        foreach ((array)$postData as $i => $field) {
            if (!isset($post_data[$key . 'deleted'][$i])) {
                LoggerManager::getLogger()->warn('AOR Condition trying to save lines but POST data does not contains the key "' . $key . 'deleted' . '" at index: ' . $i);
            }

            if (isset($post_data[$key . 'deleted'][$i]) && $post_data[$key . 'deleted'][$i] == 1) {
                $this->mark_deleted($post_data[$key . 'id'][$i]);
            } else {
                $condition = BeanFactory::newBean('AOR_Conditions');
                foreach ($this->field_defs as $field_def) {
                    $field_name = $field_def['name'];
                    if (isset($post_data[$key . $field_name][$i])) {
                        if (is_array($post_data[$key . $field_name][$i])) {
                            switch ($condition->value_type) {
                                case 'Date':
                                    $post_data[$key . $field_name][$i] = base64_encode(serialize($post_data[$key . $field_name][$i]));
                                    break;
                                default:
                                    $post_data[$key . $field_name][$i] = encodeMultienumValue($post_data[$key . $field_name][$i]);
                            }
                        } else {
                            if ($field_name == 'value' && $post_data[$key . 'value_type'][$i] === 'Value') {
                                $post_data[$key . $field_name][$i] = fixUpFormatting($_REQUEST['report_module'], $condition->field, $post_data[$key . $field_name][$i]);
                            } else {
                                if ($field_name == 'parameter') {
                                    $post_data[$key . $field_name][$i] = isset($post_data[$key . $field_name][$i]);
                                } else {
                                    if ($field_name == 'module_path') {
                                        $post_data[$key . $field_name][$i] = base64_encode(serialize(explode(":", $post_data[$key . $field_name][$i])));
                                    }
                                }
                            }
                        }
                        if ($field_name === 'parenthesis' && $post_data[$key . $field_name][$i] !== 'START') {
                            if (!isset($lastParenthesisStartConditionIds)) {
                                throw new Exception('a closure parenthesis has no starter pair');
                            }
                            $condition->parenthesis = array_pop($lastParenthesisStartConditionIds);
                        } else {
                            $condition->$field_name = $post_data[$key . $field_name][$i];
                        }
                    } else {
                        if ($field_name == 'parameter') {
                            $condition->$field_name = 0;
                        }
                    }
                }

                if ($condition->field !== null && $condition->field !== '' && !in_array($condition->field, $allowed_fields, true)) {
                    LoggerManager::getLogger()->warn(
                        'AOR_Condition: Invalid field value rejected: ' . $condition->field
                    );
                    $condition->field = '';
                }

                // value holds a column name (not a literal) when value_type is 'Field' -
                // same injection shape as `field`, needs the same allowlist.
                if ($condition->value_type === 'Field' && $condition->value !== null && $condition->value !== '' && !in_array($condition->value, $allowed_fields, true)) {
                    LoggerManager::getLogger()->warn(
                        'AOR_Condition: Invalid value (Field type) rejected: ' . $condition->value
                    );
                    $condition->value = '';
                }

                // When value_type is 'Date', value is base64(serialize([$params[0..3]])) and
                // $params[0] holds either a literal keyword ('now'/'today') or a column name -
                // same injection shape as `field`/`Field` above, needs the same allowlist.
                // $params[2] (interval amount) and $params[3] (interval unit) also reach raw SQL
                // concatenation in AOR_Report.php's DATEADD()/DATE_ADD() branch, so both are validated
                // here too (defense layer #1 - the runtime guard in AOR_Report.php is layer #2).
                if ($condition->value_type === 'Date' && $condition->value !== null && $condition->value !== '') {
                    $dateParams = @unserialize(base64_decode((string) $condition->value), ['allowed_classes' => false]);
                    $dateParamsValid = is_array($dateParams) && isset($dateParams[0]);

                    if ($dateParamsValid
                        && !in_array($dateParams[0], ['now', 'today'], true)
                        && !in_array($dateParams[0], $allowed_fields, true)
                    ) {
                        LoggerManager::getLogger()->warn(
                            'AOR_Condition: Invalid value (Date type) rejected: ' . $dateParams[0]
                        );
                        $dateParamsValid = false;
                    }

                    // 'business_hours' is a legitimate $params[3] value handled by its own branch in
                    // AOR_Report::build_report_query_where(); every other value must be a known SQL
                    // interval unit to be safe for raw concatenation into DATEADD()/DATE_ADD().
                    $allowedDateUnits = ['day', 'hour', 'minute', 'second', 'month', 'year', 'week', 'business_hours'];
                    if ($dateParamsValid && isset($dateParams[3]) && !in_array($dateParams[3], $allowedDateUnits, true)) {
                        LoggerManager::getLogger()->warn(
                            'AOR_Condition: Invalid value (Date type) interval unit rejected: ' . $dateParams[3]
                        );
                        $dateParamsValid = false;
                    }

                    if (!$dateParamsValid) {
                        $condition->value = base64_encode(serialize(['', '', '', '']));
                    } elseif (isset($dateParams[2])) {
                        $dateParams[2] = (int) $dateParams[2];
                        $condition->value = base64_encode(serialize($dateParams));
                    }
                }

                // Period must be saved as a string instead of a base64 encoded datetime.
                // Overwriting value
                if ((!isset($condition->parenthesis) || !$condition->parenthesis) && isset($condition->value_type) && $condition->value_type == 'Period') {
                    $condition->value = base64_encode($_POST['aor_conditions_value'][$i]);
                }
                if (trim($condition->field) != '' || $condition->parenthesis) {
                    if (isset($_POST['aor_conditions_order'][$i])) {
                        $condition->condition_order = (int)$_POST['aor_conditions_order'][$i];
                    } else {
                        $condition->condition_order = ++$j;
                    }
                    $condition->aor_report_id = $parent->id;
                    $conditionId = $condition->save();
                    if ($condition->parenthesis === 'START') {
                        $lastParenthesisStartConditionIds[] = $conditionId;
                    }
                }
            }
        }
    }
}
