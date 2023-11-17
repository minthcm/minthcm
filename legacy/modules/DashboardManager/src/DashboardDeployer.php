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

class DashboardDeployer
{

    protected $_bean;
    protected $_log;
    protected $_related_history_record;
    protected $_user_count;

    public function __construct(DashboardManager $dashboard = null)
    {
        if (isset($dashboard)) {
            $this->setBean($dashboard);
        }
        $this->_log = $GLOBALS['log'];
    }

    public function setBean(DashboardManager $dashboard)
    {
        $this->_bean = $dashboard;
        return $this;
    }

    public function deploy()
    {
        try {
            $this->_user_count = 0;
            $this->_related_history_record = $this->_createHistoryRecord();

            $this->_deployOneTimeDashboards();
            $this->_deployForcedTabsDashboards();
            $this->_deployLockedDashboards();

            $this->_related_history_record->user_count = $this->_user_count;
            $this->_related_history_record->save(false);
        } catch (Exception $e) {
            $this->_log->fatal('[DM] DashboardDeployer failed: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function deployForRole($chosen_user)
    {
        try {
            $this->_related_history_record = $this->_createHistoryRecord();

            $this->_deployRoleDashboards($chosen_user);

            $this->_related_history_record->user_count = 1;
            $this->_related_history_record->save(false);
        } catch (Exception $e) {
            $this->_log->fatal('[DM] DashboardDeployer failed: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    protected function _deployOneTimeDashboards()
    {
        if ($this->_bean->load_relationship('users_one_time_default_dashboards')) {
            $users = $this->_bean->users_one_time_default_dashboards->getBeans();
            foreach ($users as $user) {
                $this->_deploy($user, $this->_bean->pages, $this->_bean->dashlets);
            }
        } else {
            throw new Exception('Can not load relationship: users_one_time_default_dashboards');
        }
    }

    protected function _deployRoleDashboards($chosen_user)
    {
        if ($this->_bean->load_relationship('users_one_time_default_dashboards')) {
            $this->_bean->users_one_time_default_dashboards->add($chosen_user->id);
            $this->_deploy($chosen_user, $this->_bean->pages, $this->_bean->dashlets);
        } else {
            throw new Exception('Can not load relationship: users_one_time_default_dashboards');
        }
    }

    protected function _deployForcedTabsDashboards()
    {
        if ($this->_bean->load_relationship('users_forced_tabs_dashboards')) {
            $users = $this->_bean->users_forced_tabs_dashboards->getBeans();

            $pages = $this->_bean->pages;
            $dashlets = $this->_bean->dashlets;

            foreach ($pages as $key => $page) {
                $pages[$key]['DMForced'] = true;
            }
            foreach ($users as $user) {
                $this->_deploy($user, $pages, $dashlets);
            }
        } else {
            throw new Exception('Can not load relationship: users_forced_tabs_dashboards');
        }
    }

    protected function _deployLockedDashboards()
    {
        if ($this->_bean->load_relationship('users_locked_dashboards')) {
            $users = $this->_bean->users_locked_dashboards->getBeans();

            $pages = $this->_bean->pages;
            $dashlets = $this->_bean->dashlets;
            foreach ($pages as $key => $page) {
                $pages[$key]['DMLocked'] = true;
            }

            foreach ($users as $user) {
                $this->_deploy($user, $pages, $dashlets);
            }
        } else {
            throw new Exception('Can not load relationship: users_locked_dashboards');
        }
    }

    protected function _deploy(User $user, $pages, $dashlets)
    {
        $this->_makeDashboardCopy($user);
        $this->_user_count++;

        $user->setPreference('pages', $pages, 0, 'Home');
        $user->setPreference('dashlets', $dashlets, 0, 'Home');
        $user->savePreferencesToDB();
    }

    protected function _makeDashboardCopy(User $user)
    {
        $user->_userPreferenceFocus->reloadPreferences('Home');

        $bean = BeanFactory::getBean('DashboardBackups');
        $bean->assigned_user_id = $user->id;
        $bean->pages = $user->getPreference('pages', 'Home');
        $bean->dashlets = $user->getPreference('dashlets', 'Home');
        $bean->name = $this->_related_history_record->name . ' - ' . $user->first_name . ' ' . $user->last_name;
        $bean->dashboardmanager_id = $this->_bean->id;
        $bean->dashboardhistory_id = $this->_related_history_record->id;
        $bean->save(false);
    }

    protected function _createHistoryRecord()
    {
        global $current_user, $timedate;
        $bean = BeanFactory::getBean('DashboardHistory');
        if ($bean) {
            $bean->id = create_guid();
            $bean->new_with_id = true;
            $bean->name = $this->_bean->name . ' - ' . $timedate->nowDbDate();
            $bean->assigned_user_id = $current_user->id;
            $bean->dashboardmanager_id = $this->_bean->id;
            return $bean;
        } else {
            throw new Exception('Can not create DashboardHistory bean');
        }
    }

}
