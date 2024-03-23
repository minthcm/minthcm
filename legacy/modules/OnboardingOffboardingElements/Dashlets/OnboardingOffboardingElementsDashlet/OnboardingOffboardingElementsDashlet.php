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

require_once 'include/Dashlets/DashletGeneric.php';
require_once 'modules/OnboardingOffboardingElements/OnboardingOffboardingElements.php';

class OnboardingOffboardingElementsDashlet extends DashletGeneric
{

    public function __construct($id, $def = null)
    {

        require 'modules/OnboardingOffboardingElements/metadata/dashletviewdefs.php';

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_MODULE_TITLE',
                'OnboardingOffboardingElements');
        }
        $this->searchFields = $dashletData['OnboardingOffboardingElementsDashlet']['searchFields'];
        $this->columns = $dashletData['OnboardingOffboardingElementsDashlet']['columns'];
        $this->seedBean = BeanFactory::newBean('OnboardingOffboardingElements');
    }

    public function process($lvsParams = array(), $id = null)
    {
        global $current_language, $app_list_strings, $current_user;
        $mod_strings = return_module_language($current_language,
            'OnboardingOffboardingElements');

        if ($this->myItemsOnly) {
            $lvsParams['custom_where'] = " AND (onboardingoffboardingelements.assigned_user_id = '{$current_user->id}) ";
        }

        $this->myItemsOnly = false;
        $lvsParams['custom_select'] = ', onboardingoffboardingelements.date_entered ';
        $lvsParams['distinct'] = true;

        parent::process($lvsParams);

        foreach ($this->lvs->data['data'] as $rowNum => $row) {
            if (empty($this->lvs->data['data'][$rowNum]['TASK_DURATION_HOURS'])) {
                $this->lvs->data['data'][$rowNum]['TASK_DURATION'] = '0' . $mod_strings['LBL_HOURS_ABBREV'];
            } else {
                $this->lvs->data['data'][$rowNum]['TASK_DURATION'] = $this->lvs->data['data'][$rowNum]['TASK_DURATION_HOURS'] .
                    $mod_strings['LBL_HOURS_ABBREV'];
            }

            if (
                empty($this->lvs->data['data'][$rowNum]['TASK_DURATION_MINUTES']) ||
                empty($this->seedBean->minutes_values[$this->lvs->data['data'][$rowNum]['TASK_DURATION_MINUTES']])
            ) {
                $this->lvs->data['data'][$rowNum]['TASK_DURATION'] .= '00';
            } else {
                $this->lvs->data['data'][$rowNum]['TASK_DURATION'] .= $this->seedBean->minutes_values[$this->lvs->data['data'][$rowNum]['TASK_DURATION_MINUTES']];
            }
            $this->lvs->data['data'][$rowNum]['TASK_DURATION'] .= $mod_strings['LBL_MINSS_ABBREV'];
        }
    }

    public function displayOptions()
    {
        $this->processDisplayOptions();
        $this->configureSS->assign('strings',
            array(
                'general' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_GENERAL'],
                'filters' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_FILTERS'],
                'myItems' => translate('LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                    'OnboardingOffboardingElements'),
                'myFavorites' => $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_MY_FAVORITES'],
                'mySubordinates' => $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_MY_SUBORDINATES'],
                'displayRows' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_DISPLAY_ROWS'],
                'title' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_TITLE'],
                'clear' => $GLOBALS['app_strings']['LBL_CLEAR_BUTTON_LABEL'],
                'save' => $GLOBALS['app_strings']['LBL_SAVE_BUTTON_LABEL'],
                'autoRefresh' => $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH'],
            ));

        return $this->configureSS->fetch($this->configureTpl);
    }
}
