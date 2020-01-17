<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

class KRESTUserHandler {

    public function get_modules_acl() {
        global $moduleList;

        $actions = array('list', 'view', 'edit');

        $retModules = array();

        foreach (ACLController::disabledModuleList($moduleList) as $disabledModule)
            unset($moduleList[$disabledModule]);

        foreach ($moduleList as $module) {
            $retModules[$module]['acl']['enabled'] = ACLController::moduleSupportsACL($module);
            if ($retModules[$module]['acl']['enabled']) {
                foreach ($actions as $action)
                    $retModules[$module]['acl'][$action] = ACLController::checkAccess($module, $action);
            }
        }

        return $retModules;
    }

}
