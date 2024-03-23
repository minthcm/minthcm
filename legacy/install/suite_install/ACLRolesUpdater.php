<?php

class ACLRolesUpdater
{

    private $config_file = 'acl_roles.php';
    private $config_path = '';
    private $config_array = array();

    public function __construct()
    {
        include_once "modules/ACL/install_actions.php";
    }

    public function run()
    {
        $this->config_path = 'install/suite_install/' . $this->config_file;
        $this->loadConfigFromFile();
        $this->update();
    }

    private function isSugarCRMVersion()
    {
        $result = true;
        if (isset($GLOBALS['sugar_config']['suitecrm_version']) && !empty($GLOBALS['sugar_config']['suitecrm_version'])) {
            $result = false;
        }
        return $result;
    }

    private function loadConfigFromFile()
    {
        $acl_roles = array();
        if (file_exists($this->config_path)) {
            include $this->config_path;
        }
        if (!empty($acl_roles)) {
            foreach ($acl_roles as $role_array) {
                $config_subarray = array();
                if (!empty($role_array['id']) && !empty($role_array['name'])) {
                    $config_subarray['id'] = $role_array['id'];
                    $config_subarray['name'] = $role_array['name'];
                    $config_subarray['description'] = $this->getSubValue($role_array, 'description', '');
                    $config_subarray['user_ids'] = $this->getSubArray($role_array, 'user_ids');
                    $config_subarray['default_actions'] = $this->getSubArray($role_array, 'default_actions');
                    $config_subarray['actions'] = $this->getSubArray($role_array, 'actions');
                    $config_subarray['fields'] = $this->getSubArray($role_array, 'fields');
                    $config_subarray['delete_existing_role_users'] = $this->getSubValue($role_array, 'delete_existing_role_users', true);
                    $config_subarray['reset_existing_role_actions'] = $this->getSubValue($role_array, 'reset_existing_role_actions', true);
                    $config_subarray['reset_existing_role_fields'] = $this->getSubValue($role_array, 'reset_existing_role_fields', true);
                    $config_subarray['update_name'] = $this->getSubValue($role_array, 'update_name', true);
                    $config_subarray['update_description'] = $this->getSubValue($role_array, 'update_description', true);
                    $this->config_array[] = $config_subarray;
                }
            }
        }
    }

    private function update()
    {
        if (!empty($this->config_array)) {
            foreach ($this->config_array as $role_array) {
                $role = $this->saveRole($role_array);
                if ($role_array['delete_existing_role_users']) {
                    $this->deleteExistingRoleUsers($role);
                }
                if ($role_array['reset_existing_role_actions']) {
                    $this->resetExistingRoleActions($role);
                }
                if ($this->isSugarCRMVersion() && $role_array['reset_existing_role_fields']) {
                    $this->resetExistingRoleFields($role);
                }
                $this->saveRoleUsers($role, $role_array);
                $this->saveDefaultRoleActions($role, $role_array);
                $this->saveRoleActions($role, $role_array);
                if ($this->isSugarCRMVersion()) {
                    $this->saveRoleFields($role, $role_array);
                }
            }
        }
    }

    private function saveRole($role_array)
    {
        $id = $role_array['id'];
        $name = $role_array['name'];
        $description = $role_array['description'];
        $update_name = $role_array['update_name'];
        $update_description = $role_array['update_description'];
        $role = new ACLRole();
        $role->retrieve($id);
        if (empty($role->id)) {
            $role->id = $id;
            $role->new_with_id = true;
            $role->name = $name;
            $role->description = $description;
        } else {
            if ($update_name) {
                $role->name = $name;
            }
            if ($update_description) {
                $role->description = $description;
            }
        }
        $role->save();
        return $role;
    }

    private function deleteExistingRoleUsers($role)
    {
        $role->load_relationship('users');
        $user_beans = $role->users->getBeans();
        if (!empty($user_beans)) {
            foreach ($user_beans as $user_bean) {
                $role->users->delete($role->id, $user_bean->id);
            }
        }
    }

    private function resetExistingRoleActions($role)
    {
        $role_actions_modules = (new ACLRole())->getRoleActions($role->id);
        foreach ($role_actions_modules as $module) {
            foreach ($module as $module_action) {
                foreach ($module_action as $action_array) {
                    $action_id = $action_array['id'];
                    $access = $action_array['aclaccess'];
                    if ($access != 0) {
                        $access = 0;
                        $role->setAction($role->id, $action_id, $access);
                    }
                }
            }
        }
    }

    private function resetExistingRoleFields($role)
    {
        $role_fields = ACLField::getACLFieldsByRole($role->id);

        foreach ($role_fields as $field_array) {
            $field_name = $field_array['name'];
            $module_name = $field_array['category'];
            $access = $field_array['aclaccess'];
            if ($access != 0) {
                $access = 0;
                ACLField::setAccessControl($module_name, $role->id, $field_name, $access);
            }
        }
    }

    private function saveRoleUsers($role, $role_array)
    {
        $user_ids = $role_array['user_ids'];
        if (!empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                if (!empty($user_id)) {
                    $user = BeanFactory::getBean('Users', $user_id);
                    if ($user && !empty($user->id)) {
                        $relationship_data = array(
                            'role_id' => $role->id,
                            'user_id' => $user->id,
                        );
                        $role->set_relationship('acl_roles_users', $relationship_data, true, true, array());
                    }
                }
            }
        }
    }

    private function saveDefaultRoleActions($role, $role_array)
    {
        $default_actions = $role_array['default_actions'];
        if (!empty($default_actions)) {
            $role_actions_modules = (new ACLRole())->getRoleActions($role->id);
            foreach ($role_actions_modules as $module_name => $module_array) {
                foreach ($default_actions as $module_action => $access) {
                    if ($this->isDifferentActionAccess($role_actions_modules, $module_name, $module_action, $access)) {
                        $action_id = $role_actions_modules[$module_name]['module'][$module_action]['id'];
                        $role->setAction($role->id, $action_id, $access);
                    }
                }
            }
        }
    }

    private function saveRoleActions($role, $role_array)
    {
        $actions = $role_array['actions'];
        if (!empty($actions)) {
            $role_actions_modules = (new ACLRole())->getRoleActions($role->id);
            foreach ($actions as $module_name => $module_actions) {
                foreach ($module_actions as $module_action => $access) {
                    if ($this->isDifferentActionAccess($role_actions_modules, $module_name, $module_action, $access)) {
                        $action_id = $role_actions_modules[$module_name]['module'][$module_action]['id'];
                        $role->setAction($role->id, $action_id, $access);
                    }
                }
            }
        }
    }

    private function isDifferentActionAccess($role_actions_modules, $module_name, $module_action, $access)
    {
        $result = false;
        if (
            !empty($module_action) &&
            is_numeric($access) &&
            isset($role_actions_modules[$module_name]['module'][$module_action]['aclaccess']) &&
            isset($role_actions_modules[$module_name]['module'][$module_action]['id']) &&
            $role_actions_modules[$module_name]['module'][$module_action]['aclaccess'] != $access
        ) {
            $result = true;
        }
        return $result;
    }

    private function saveRoleFields($role, $role_array)
    {
        $fields = $role_array['fields'];
        if (!empty($fields)) {
            foreach ($fields as $module_name => $module_fields) {
                foreach ($module_fields as $field_name => $access) {
                    if (!empty($field_name) && is_numeric($access)) {
                        ACLField::setAccessControl($module_name, $role->id, $field_name, $access);
                    }
                }
            }
        }
    }

    private function getSubValue($array, $key, $dafault_value)
    {
        $result = $dafault_value;
        if (isset($array[$key])) {
            $result = $array[$key];
        }
        return $result;
    }

    private function getSubArray($array, $key)
    {
        $result = array();
        if (isset($array[$key]) && !empty($array[$key])) {
            $result = $array[$key];
        }
        return $result;
    }

}
