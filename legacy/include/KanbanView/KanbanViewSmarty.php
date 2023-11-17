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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class KanbanViewSmarty
{
    public $columns;
    public $data;
    public $ss; // the smarty object
    public $tpl;
    public $moduleString;
    public $seed;
    public $templateMeta = array();

    /**
     * Constructor, Smarty object immediately available after
     *
     */
    public function __construct()
    {
        $this->ss = new Sugar_Smarty();
    }

    public function setup($seed, $file)
    {

        $this->seed = $seed;
        $this->process($file);

        return true;
    }

    /**
     * Processes the request. Calls ListViewData process. Also assigns all lang strings, export links,
     * This is called from ListViewDisplay
     *
     * @param file $file Template file to use
     *
     */
    function process($file)
    {
        global $mod_strings;
        global $app_strings;


        $this->tpl = $file;

        $this->ss->assign('module', $this->seed->module_name);
        $this->ss->assign('sugarconfig', $this->displayColumns);
        $this->ss->assign('displayColumns', $this->displayColumns);
        $this->ss->assign('options', isset($this->templateMeta['options']) ? $this->templateMeta['options']
                    : null);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('columns', $this->columns);
        $this->ss->assign('data', $this->data);
    }

    /**
     * Displays the xtpl, either echo or returning the contents
     *
     */
    function display()
    {
        $this->ss->assign('json', json_encode($this->prepareData()));
        return $this->ss->fetch($this->tpl);
    }

    protected function prepareData()
    {
        return $this->data;
    }
}