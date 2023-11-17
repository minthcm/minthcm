<?php

require_once 'include/LastNextContacts/LastNextContactsConfig.php';
require_once 'include/LastNextContacts/LastNextContactsBase.php';
require_once 'include/LastNextContacts/LastNextContactsQueue.php';

class LastNextContactsHooks extends LastNextContactsBase
{

    public function beforeEmailUpdate($bean, $events, $arguments)
    {
        if (
            "Emails" == $bean->module_name
            && isset($bean->field_defs['date_sent_received'])
            && isset($bean->date_sent)
            && !isset($bean->date_sent_received)
        ) {
            $bean->date_sent_received = $bean->date_sent;
        }
    }
    public function afterRelationshipAdd($bean, $event, $arguments)
    {
        if ($this->addRelatedBeanToJob($arguments)) {
            $last_next_contacts_queue = $this->getLNCQueue();
            $last_next_contacts_queue->createJobRelations($arguments);
        }
    }

    public function afterRelationshipDelete($bean, $event, $arguments)
    {
        if ($this->addRelatedBeanToJob($arguments)) {
            $last_next_contacts_queue = $this->getLNCQueue();
            $last_next_contacts_queue->createJobRelations($arguments);
        }
    }

    public function storeRelations($bean, $event, $arguments)
    {
        foreach (LastNextContactsConfig::get('last_next_modules') as $module) {
            $module_lowercase = strtolower($module);
            if (is_object($bean->$module_lowercase)) {
                $beans = $this->getAllRelatedBeans($bean->$module_lowercase->rows, $module);
                if (count($beans) > 0) {
                    $this->setSessionValue($module_lowercase, $beans);
                }
            }
        }
    }

    protected function getAllRelatedBeans($related_beans, $module)
    {
        $beans = array();
        if ($module) {
            foreach ($related_beans as $id => $v) {
                if (!$id) {
                    continue;
                }
                $related_bean = BeanFactory::getBean($module, $id);
                if (null !== $related_bean) {
                    $beans[] = $related_bean;
                }
            }
        }
        return $beans;
    }

    public function afterBeanUpdate($bean, $events, $arguments)
    {
        if (
            (
                in_array($bean->module_name, LastNextContactsConfig::get('last_next_activities_std_modules'))
                || in_array($bean->module_name, LastNextContactsConfig::get('last_next_modules'))
            )
            && $this->isImportantSave($bean, $arguments)
        ) {
            $data = array(
                'bean_module_name' => $bean->module_name,
                'bean_id' => $bean->id,
                'related_module' => $bean->module_name,
                'related_id' => $bean->id,
                'related_modules' => $this->getBeansDataFromRelatedModule(),
            );
            $last_next_contacts_queue = $this->getLNCQueue();
            if (in_array($bean->module_name, LastNextContactsConfig::get('last_next_modules'))) {
            $last_next_contacts_queue->createJobBean($data);
            } else {
                $last_next_contacts_queue->createJobBeanReleted($data);
            }
        }
    }

    public function afterBeanDelete($bean)
    {
        if (in_array($bean->module_name, LastNextContactsConfig::get('last_next_activities_std_modules'))) {
            $data = array(
                'bean_module_name' => $bean->module_name,
                'bean_id' => $bean->id,
                'related_module' => $bean->module_name,
                'related_id' => $bean->id,
                'related_modules' => $this->getBeansDataFromRelatedModule(),
            );
            $last_next_contacts_queue = $this->getLNCQueue();
            $last_next_contacts_queue->createJobRelations($data);
        }
    }

    public function afterEmailImport($bean, $events, $arguments)
    {
        if (
            (
                in_array($bean->module_name, LastNextContactsConfig::get('last_next_activities_std_modules'))
                || in_array($bean->module_name, LastNextContactsConfig::get('last_next_modules'))
            )
            && $this->isImportantSave($bean, $arguments)
        ) {
            $data = array(
                'bean_module_name' => $bean->module_name,
                'bean_id' => $bean->id,
                'related_module' => $bean->module_name,
                'related_id' => $bean->id,
                'related_modules' => $this->getBeansDataFromRelatedModule(),
            );
            $last_next_contacts_queue = $this->getLNCQueue();
            if (in_array($bean->module_name, LastNextContactsConfig::get('last_next_modules'))) {
            $last_next_contacts_queue->createJobBean($data);
            } else {
                $last_next_contacts_queue->createJobBeanReleted($data);
            }
        }
    }

    protected function getBeansDataFromRelatedModule()
    {
        $return_data = array();
        foreach (LastNextContactsConfig::get('last_next_modules') as $module) {
            if ($this->isSetSessionValue($module)) {
                foreach ($this->getSessionValue($module) as $related_bean) {
                    $return_data[] = array(
                        'bean_module_name' => $related_bean->module_name,
                        'bean_id' => $related_bean->id,
                    );
                }
            }
        }
        return $return_data;
    }

    protected function addRelatedBeanToJob($arguments)
    {
        if (
            isset($arguments['module'])
            && isset($arguments['related_module'])
            && in_array($arguments['related_module'], LastNextContactsConfig::get('last_next_modules'))
            && (
                in_array($arguments['module'], LastNextContactsConfig::get('last_next_activities_std_modules'))
                ||
                in_array($arguments['module'], LastNextContactsConfig::get('last_next_modules'))
            )
        ) {
            return true;
        }
        return false;
    }

    protected function isImportantSave($bean, $arguments)
    {
        $result = true;
        if (
            LastNextContactsConfig::cisset('last_next_trigger_fields', $bean->module_name) &&
            isset($arguments['isUpdate']) &&
            $arguments['isUpdate'] &&
            isset($arguments['dataChanges']) &&
            is_array($arguments['dataChanges']) &&
            count($arguments['dataChanges']) > 0
        ) {
            $result = false;

            $important_fields = LastNextContactsConfig::get('last_next_trigger_fields', $bean->module_name);
            if (is_array($important_fields)) {
                foreach ($important_fields as $important_field) {
                if (isset($arguments['dataChanges'][$important_field])) {
                    $result = true;
                    }
                }
            }
        }
        if (
            isset($arguments['isUpdate'])
            && $arguments['isUpdate']
            && LastNextContactsConfig::get('only_owner_activities_enabled')
            && isset($arguments['dataChanges']['assigned_user_id'])
        ) {
            $result = true;
        }

        if ($result && false === $bean->fetched_row && empty($bean->email)) {
            $result = false;
        }
        return $result;
    }

    private function getLNCQueue()
    {
        return new LastNextContactsQueue;
    }
    private function setSessionValue($key, $beans)
    {
        $_SESSION[$this->sessionKey($key)] = $beans;
    }
    private function getSessionValue($key)
    {
        return $_SESSION[$this->sessionKey($key)] = $beans;
    }
    private function isSetSessionValue($key)
    {
        return isset($_SESSION[$this->sessionKey($key)]);
    }
    private function sessionKey($key)
    {
        return 'bean_' . strtolower($key);
    }

}
