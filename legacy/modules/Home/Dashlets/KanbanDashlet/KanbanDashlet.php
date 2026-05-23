<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/Dashlets/Dashlet.php';
require_once 'include/Sugar_Smarty.php';

class KanbanDashlet extends Dashlet
{
    public $kanban_module = 'Tasks'; 

    public function __construct($id, $def)
    {
        $this->loadLanguage('KanbanDashlet');

        if (!empty($def['title'])) {
            $this->title = $def['title'];
        } else {
            $this->title = $this->dashletStrings['LBL_TITLE'];
        }

        if (!empty($def['kanban_module'])) {
            $this->kanban_module = $def['kanban_module'];
        }
        $this->autoRefresh = false;
        parent::__construct($id);

        $this->isConfigurable = true;
    }

    public function display()
    {
        include "modules/{$this->kanban_module}/metadata/kanbanviewdefs.php";
        $json = json_encode($kanbanViewDefs[$module_name]);
        if (!empty($json) && 'null' !== $json) {
            $ss = new Sugar_Smarty();
            $ss->assign('id', $this->id);
            $ss->assign('dashletStrings', $this->dashletStrings);
            $ss->assign('columns', 1);
            $ss->assign('module', $this->kanban_module);
            $ss->assign('kanban_module', $this->kanban_module);
            $ss->assign('json', $json);
            $str = $ss->fetch('include/KanbanView/KanbanViewGeneric.tpl');
            return parent::display() . $str . '<br />';
        } else {
            return;
        }

    }

    public function displayOptions()
    {
        $ss = new Sugar_Smarty();

        $ss->assign('id', $this->id);
        $ss->assign('DASHLET_STRINGS', $this->dashletStrings);
        $ss->assign('title_label', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
        $ss->assign('save_label', $this->dashletStrings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('kanban_module_label', $this->dashletStrings['LBL_KANBAN_MODULE_LABEL']);
        $ss->assign('kanban_module_list', $GLOBALS['app_list_strings']['kanban_module_list']);
        $ss->assign('title', $this->title);
        $ss->assign('kanban_module', $this->kanban_module);
        $result = parent::displayOptions();
        $result .= $ss->fetch('modules/Home/Dashlets/KanbanDashlet/KanbanDashletOptions.tpl');
        return $result;
    }

    public function saveOptions($req)
    {
        $options = array();
        $options['title'] = $req['title'];
        $options['kanban_module'] = $req['kanban_module'];
        return $options;
    }
}
