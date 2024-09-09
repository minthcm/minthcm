<?php

namespace MintHCM\Api\Controllers;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use Exception;


/**
 * MetaController
 */
class MetaController
{
    public function getEditViewMeta($module)
    {
        chdir('../legacy/');
        $ve = new \ViewEdit;
        $ve->module = $module;
        if(!file_exists($ve->getMetaDataFile())){
            return [];
        }
        require_once $ve->getMetaDataFile();
        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->getModuleVardefs($bean);
        chdir('../api/');
        return $this->mergeModuleFields($viewdefs[$module]['EditView']['panels'],$module_fields);
    }

    public function getDetailViewMeta($module)
    {
        chdir('../legacy/');

        $vd = new \ViewDetail;
        $vd->module = $module;
        if(!file_exists($vd->getMetaDataFile())){
            return [];
        }
        require_once $vd->getMetaDataFile();

        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->getModuleVardefs($bean);

        $data = [];
        $data['detailview'] = $this->mergeModuleFields($viewdefs[$module]['DetailView']['panels'],$module_fields);

        chdir('../api/');
        return $data['detailview'];
    }

    public function getRecordViewMeta($module)
    {
        chdir('../legacy/');

        $vr = new \ViewRecord;
        $vr->module = $module;
        if(!file_exists($vr->getMetaDataFile())){
            return [];
        }
        require_once $vr->getMetaDataFile();

        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->getModuleVardefs($bean);

        $data = [];
        $views = [];
        foreach($viewdefs[$module]['panels'] as $panel=>$panel_defs){
            $views[$panel]['fields'] = $panel_defs['data']['fields'];
            $data['recordview']['panels'][$panel] = $viewdefs[$module]['panels'][$panel];
            $data['recordview']['panels'][$panel]['data']['title'] = $viewdefs[$module]['panels'][$panel]['title'];
            $data['recordview']['panels'][$panel]['data']['fields'] = $this->mergeModuleFields($views[$panel],$module_fields)['fields'];
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
                if(is_string($field)){
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
                    if(!isset($v['name']) || empty($module_fields[$v['name']])){
                        continue;
                    }
                    if(empty($array[$panel][$arr_key][$k]['label'])){
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
            if(empty($module_fields[$k])){
                unset($array[$k]);
                continue;
            }
            if(!empty($array[$k]['vname'])){
                $array[$k]['label'] = $array[$k]['vname'];
                unset($array[$k]['vname']);
            } else {
                if(!empty($module_fields[$k]['vname'])){
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
            if(!empty($module_bean) && $module_bean instanceof \SugarBean){
                $array[$name]['columns'] = $this->mergeSubpanelFields(($sb->load_subpanel($name))->panel_definition['list_fields'], $this->getModuleVardefs($module_bean));
            } else {
                $array[$name]['columns'] = ($sb->load_subpanel($name))->panel_definition['list_fields'];    
            }
        }
        return $array;
    }
}
