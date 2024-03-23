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

require_once 'install/install_utils.php';
require_once 'install/UserDemoData.php';
require_once 'install/TeamDemoData.php';

if (file_exists("include/language/{$current_language}.lang.php")) {
    require_once "include/language/{$current_language}.lang.php";
} else {
    require_once "include/language/en_us.lang.php";
}

if (file_exists("install/demoData.{$current_language}.php")) {
    require_once "install/demoData.{$current_language}.php";
} else {
    require_once "install/demoData.en_us.php";
}

global $sugar_demodata, $sugar_demodata_relations;

foreach ($sugar_demodata as $module => $records) {
    foreach ($records as $record) {
        $bean = BeanFactory::newBean($module);
        $bean->new_with_id = true;
        foreach ($record as $field_name => $value) {
            if (isset($bean->field_defs[$field_name]) && !empty($value)) {
                if (!empty($value['function'])) {
                    $arguments = $value['arguments'] ?? [];
                    $field = $arguments['field'] ?? null;
                    if ($field) {
                        if (isset($value['related_record'])) {
                            $GLOBALS['disable_date_format'] = true;
                            $rel_record = BeanFactory::getBean($value['related_record']['module'], $value['related_record']['id']);
                            $arguments['field'] = $rel_record->$field ?? '';
                        } elseif (!empty($bean->$field)) {
                            $arguments['field'] = $bean->$field;
                        }
                    }
                    $value = call_user_func_array($value['function'], array_values($arguments));
                }
                $bean->$field_name = $value;
                if ($field_name == 'assigned_user_id' || ($bean->field_defs[$field_name]['type'] == 'id' && (isset($bean->field_defs[$field_name]['relationship']) || $field_name == 'parent_id'))) {
                    $rel_field_name = str_replace('_id', '_name', $field_name);
                    $rel_module_name = $bean->field_defs[$rel_field_name]['module'] ?? $bean->parent_type;
                    if (isset($bean->field_defs[$rel_field_name]) && !empty($rel_module_name)) {
                        $rel_bean = BeanFactory::getBean($rel_module_name, $bean->$field_name);
                        $bean->$rel_field_name = $rel_bean->name;
                    }
                }
            }
        }
        $bean->skip_vt_validation = true;
        try {
            $bean->save();
        } catch (Throwable $e) {
            $GLOBALS['log']->fatal("[MintHCM Demo Data] Can't save {$module} id: {$record['id']}");
        }
    }
}

foreach ($sugar_demodata_relations as $module => $relations) {
    foreach ($relations as $rel_name => $ids) {
        $link_field = getRelationshipLinkFieldName($module, $rel_name);
        foreach ($ids as $bean_id => $rel_beans_ids) {
            $bean = BeanFactory::getBean($module, $bean_id);
            if ($bean && !empty($bean->id) && $bean->load_relationship($link_field)) {
                $bean->$link_field->add($rel_beans_ids);
            }
        }
    }
}

$ws_ids = [];
foreach ($sugar_demodata['WorkSchedules'] as $ws) {
    if (!empty($ws['status']) && $ws['status'] == 'closed') {
        $ws_ids[] = $ws['id'];
    }
}
if (!empty($ws_ids)) {
    global $db;
    $sql = "UPDATE workschedules SET status = 'closed' WHERE id IN ('" . implode("','", $ws_ids) . "')";
    $db->query($sql);
}
