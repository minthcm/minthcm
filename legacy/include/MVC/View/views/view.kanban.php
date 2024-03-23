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
require_once('include/MVC/View/SugarView.php');

require_once('include/KanbanView/KanbanViewSmarty.php');

class ViewKanban extends SugarView
{
    /**
     * @var string $type
     */
    public $type = 'kanban';

    /**
     * @var KanbanViewSmarty $kv
     */
    public $kv;

    /**
     * @var SugarBean
     */
    public $seed;

    /**
     * @var array $kanbanViewDefs
     */
    public $kanbanViewDefs;

    /**
     * ViewKanban constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prepare Kanban View
     */
    public function kanbanViewPrepare()
    {
        $module = isset($GLOBALS['module']) ? $GLOBALS['module'] : null;

        if (!isset($module)) {
            LoggerManager::getLogger()->fatal('Undefined module for kanban view prepare');
            return false;
        }

        $metadataFile = $this->getMetaDataFile();

        if (!file_exists($metadataFile)) {
            sugar_die(sprintf($GLOBALS['app_strings']['LBL_NO_ACTION'], $this->do_action));
        }

        require($metadataFile);

        $this->kanbanViewDefs = $kanbanViewDefs;

        if (isset($viewdefs[$this->module]['KanbanView']['templateMeta'])) {
            $this->kv->templateMeta = $viewdefs[$this->module]['KanbanView']['templateMeta'];
        }


        $this->seed = $this->bean;

        $data = $this->kanbanViewDefs[$module];
        $columns = $this->kanbanViewDefs[$module]['columns'];
        if (empty($columns)) {
            LoggerManager::getLogger()->fatal('Columns for Kanban View is not defined');
        }
        $black_list = $this->kanbanViewDefs[$module]['black_list'];

        if (!isset($this->kv) || !$this->kv) {
            $this->kv = new stdClass();
        }

        if (!isset($this->kv)) {
            $this->kv = new stdClass();
            LoggerManager::getLogger()->warn('Kanban view is not defined');
        }

        $this->kv->columns = $columns;
        $this->kv->data = $data;

        $this->module = $module;


        if (isset($this->options['show_title']) && $this->options['show_title']) {

            echo $this->getModuleTitle(true);
        }
    }

    /**
     * Process Kanban View
     */
    public function kanbanViewProcess()
    {

        $this->kv->setup($this->seed, $this->getTplFile());
        echo $this->kv->display();
    }

    public function getTplFile()
    {
        $module_path = 'modules/'.$this->module.'/include/KanbanView/KanbanViewGeneric.tpl';
        if (file_exists('custom/'.$module_path)) {
            return 'custom/'.$module_path;
        } else if (file_exists($module_path)) {
            return $module_path;
        }
        $include_path = 'include/KanbanView/KanbanViewGeneric.tpl';
        if (file_exists('custom/'.$include_path)) {
            return 'custom/'.$include_path;
        } else if (file_exists($include_path)) {
            return $include_path;
        }
        $GLOBALS['log']->fatal("Kanban TPL file does not exist");
        return '';
    }

    /**
     * Setup View
     */
    public function preDisplay()
    {
        $this->kv = new KanbanViewSmarty();
    }

    /**
     * Display View
     */
    public function display()
    {
        if (!$this->bean || !$this->bean->ACLAccess('list')) {
            ACLController::displayNoAccess();
        } else {
            $this->kanbanViewPrepare();
            $this->kanbanViewProcess();
        }
    }
    protected function _displayJavascript()
    {
        parent::_displayJavascript();
        $this->displayCurrentUserDataJS();
    }

    protected function displayCurrentUserDataJS()
    {
        global $current_user;
        $current_user->load_Relationship('aclroles');
        $data = [
            'id' => $current_user->id,
            'first_name' => $current_user->first_name,
            'last_name' => $current_user->last_name,
            'roles' => $current_user->aclroles->get(),
        ];
        echo '<script>window.current_user = ' . json_encode($data) . ';</script>';
    }
}