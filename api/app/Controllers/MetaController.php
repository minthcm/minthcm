<?php

namespace MintHCM\Api\Controllers;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * MetaController
 */
class MetaController
{
    protected const FIELDS_IN_OTHER_SECTION = [
        [
            'assigned_user_name',
        ],
        [
            'date_entered',
            'date_modified',
        ],
    ];

    protected const DEFAULT_ACTIONS = [
        'Audit',
        'Delete',
        'Duplicate',
    ];

    public function getEditViewMeta($module)
    {
        chdir('../legacy/');
        $ve = new \ViewEdit;
        $ve->module = $module;
        if (!file_exists($ve->getMetaDataFile())) {
            return [];
        }
        include $ve->getMetaDataFile();
        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->getModuleVardefs($bean);
        chdir('../api/');
        return $this->mergeModuleFields($viewdefs[$module]['EditView']['panels'], $module_fields);
    }

    public function getDetailViewMeta($module)
    {
        chdir('../legacy/');

        $vd = new \ViewDetail;
        $vd->module = $module;
        if (!file_exists($vd->getMetaDataFile())) {
            return [];
        }
        include $vd->getMetaDataFile();

        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->getModuleVardefs($bean);

        $data = [];
        $data['detailview'] = $this->mergeModuleFields($viewdefs[$module]['DetailView']['panels'], $module_fields);

        chdir('../api/');
        return $data['detailview'];
    }

    public function getRecordViewMeta($module)
    {
        chdir('../legacy/');

        $vr = new \ViewRecord;
        $vr->module = $module;
        if (!file_exists($vr->getMetaDataFile())) {
            return [];
        }
        include $vr->getMetaDataFile();

        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->getModuleVardefs($bean);

        $data = [];
        $views = [];
        foreach ($viewdefs[$module]['panels'] as $panel => $panel_defs) {
            $data['recordview']['panels'][$panel]['component'] = $viewdefs[$module]['panels'][$panel]['component'];
            if ('MintPanelRecordDetails' === $panel_defs['component']) {
                foreach ($panel_defs['data']['sections'] as $section => $section_defs) {
                    $views[$panel][$section]['fields'] = $section_defs['fields'] ?? [];
                    $data['recordview']['panels'][$panel]['data']['sections'][$section] = $viewdefs[$module]['panels'][$panel]['data']['sections'][$section];
                    $data['recordview']['panels'][$panel]['data']['sections'][$section]['fields'] = $this->mergeModuleFields($views[$panel][$section], $module_fields)['fields'];
                }
                $data['recordview']['panels'][$panel]['data']['actions'] = $viewdefs[$module]['panels'][$panel]['data']['actions'];
                if (!in_array('other', array_keys($panel_defs['data']['sections']))) {
                    $views[$panel]['other']['fields'] = self::FIELDS_IN_OTHER_SECTION;
                    $data['recordview']['panels'][$panel]['data']['sections']['other'] = [
                        'title' => 'LBL_OTHER',
                        'collapsed' => true,
                    ];
                    $data['recordview']['panels'][$panel]['data']['sections']['other']['fields'] = $this->mergeModuleFields($views[$panel]['other'], $module_fields)['fields'];
                }
                
                if (!isset($panel_defs['data']['actions'])) {
                    $data['recordview']['panels'][$panel]['data']['actions'] = self::DEFAULT_ACTIONS;
                }

                continue;
            }
            $views[$panel]['fields'] = $panel_defs['data']['fields'];
            $data['recordview']['panels'][$panel] = $viewdefs[$module]['panels'][$panel];
            $data['recordview']['panels'][$panel]['data']['title'] = $viewdefs[$module]['panels'][$panel]['title'];
            $data['recordview']['panels'][$panel]['data']['fields'] = $this->mergeModuleFields($views[$panel], $module_fields)['fields'];
        }
        $data['recordview']['order'] = $viewdefs[$module]['order'];

        chdir('../api/');
        return $data['recordview'];
    }

    public function getSubpanelsMeta($module)
    {
        chdir('../legacy/');

        require_once 'include/SubPanel/SubPanelDefinitions.php';

        $bean = \BeanFactory::newBean($module);

        $data = [];
        $data['subpanels'] = $this->getSubpanelSetup(new \SubPanelDefinitions($bean, $module));

        chdir('../api/');
        return $data['subpanels'];
    }

    protected function mergeModuleFields($array, $module_fields)
    {
        foreach ($array as $panel => $fields) {
            foreach ($fields as $arr_key => $field) {
                if (is_string($field)) {
                    $array[$panel][$arr_key] = $module_fields[$field];
                    continue;
                }
                foreach ($field as $k => $v) {
                    if (!is_array($v) && !empty($module_fields[$v])) {
                        $array[$panel][$arr_key][$k] = $module_fields[$v];
                        $array[$panel][$arr_key][$k]['label'] = $array[$panel][$arr_key][$k]['vname'];
                        unset($array[$panel][$arr_key][$k]['vname']);
                        continue;
                    }
                    if (isset($v['type']) && 'fieldset' === $v['type'] && isset($v['properties']['fields']) && is_array($v['properties']['fields'])) {
                        foreach ($v['properties']['fields'] as $fieldset_index => $fieldset_field) {
                            $field_name = $fieldset_field['name'] ?? $fieldset_field;
                            $field_data = $module_fields[$field_name];
                            if (is_array($fieldset_field)) {
                                $array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index] = array_merge($module_fields[$field_name], $fieldset_field);
                                $array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index]['label'] = $array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index]['vname'];
                                unset($array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index]['vname']);
                            } else {
                                $array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index] = $field_data;
                                $array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index]['label'] = $array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index]['vname'];
                                unset($array[$panel][$arr_key][$k]['properties']['fields'][$fieldset_index]['vname']);
                            }
                        }
                        continue;
                    }
                    if (!isset($v['name']) || empty($module_fields[$v['name']])) {
                        continue;
                    }
                    if (empty($array[$panel][$arr_key][$k]['label'])) {
                        $array[$panel][$arr_key][$k]['label'] = $module_fields[$v['name']]['vname'];
                    }
                    unset($module_fields[$v['name']]['vname']);
                    $array[$panel][$arr_key][$k] += $module_fields[$v['name']];
                }
            }
        }
        return $array;
    }

    public function getModuleVardefs($bean)
    {
        $arr = [];
        if (!empty($bean) && $bean instanceof \SugarBean) {
            $arr = $bean->getFieldDefinitions();
        }
        return $arr;
    }

    private function mergeSubpanelFields($array, $module_fields)
    {
        foreach ($array as $k => $v) {
            if (!is_array($v) && !empty($module_fields[$v])) {
                $array[$k] = $module_fields[$v];
                $array[$k]['label'] = $array[$k]['vname'];
                unset($array[$k]['vname']);
                continue;
            }
            if (in_array($k, ['edit_button', 'remove_button'])) {
                continue;
            }
            if (empty($module_fields[$k])) {
                unset($array[$k]);
                continue;
            }
            if (!empty($array[$k]['vname'])) {
                $array[$k]['label'] = $array[$k]['vname'];
                unset($array[$k]['vname']);
            } else {
                if (!empty($module_fields[$k]['vname'])) {
                    $array[$k]['label'] = $module_fields[$k]['vname'];
                }
            }
            unset($module_fields[$k]['vname']);
            $array[$k] += $module_fields[$k];
        }
        return $array;
    }

    private function getSubpanelSetup($sb)
    {
        $array = [];
        foreach ($sb->layout_defs['subpanel_setup'] as $name => $defs) {
            $module_bean = \BeanFactory::newBean($defs['module']);
            $array[$name]['properties'] = $defs;
            if (!empty($module_bean) && $module_bean instanceof \SugarBean) {
                $columns = $this->mergeSubpanelFields(($sb->load_subpanel($name))->panel_definition['list_fields'], $this->getModuleVardefs($module_bean));
                $array[$name]['columns'] = $this->resolveFieldFunctionOptions($columns ?? []);
            } else {
                $array[$name]['columns'] = ($sb->load_subpanel($name))->panel_definition['list_fields'];
            }
        }
        return $array;
    }

    /**
     * For enum/multienum columns whose options come from a custom function (vardef 'function' key)
     * rather than a named dropdown list, call that function and store the resulting key→label map
     * directly in the 'options' key. This mirrors the MintLogic function_options.php pattern and
     * lets enum.list.vue resolve labels without any extra frontend work.
     */
    private function resolveFieldFunctionOptions(array $columns): array
    {
        foreach ($columns as $key => $column) {
            if (
                !isset($column['type']) ||
                !in_array($column['type'], ['enum', 'multienum'], true) ||
                !isset($column['function'])
            ) {
                continue;
            }
            $function = $column['function'];
            if (!empty($function['include'])) {
                require_once $function['include'];
            }
            $function_name = $function['name'] ?? '';
            if (!empty($function_name) && function_exists($function_name)) {
                $result = call_user_func(
                    $function_name,
                    null,
                    $key,
                    '',
                    'list',
                    $function['additional_params'] ?? ''
                );
                if (!empty($result)) {
                    $columns[$key]['options'] = $result;
                }
            }
        }
        return $columns;
    }
}
