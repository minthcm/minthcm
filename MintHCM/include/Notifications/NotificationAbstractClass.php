<?php

/**
 * We are added Abstract Class and Null class beacuse
 * if notification does not be return for process,
 * system returns Null object in order not to stop whole process
 */
abstract class NotificationAbstractClass
{

    protected $name;
    protected $description;
    protected $assigned_user_id;
    protected $related_bean_type;
    protected $related_bean_id;
    protected $type;
    protected $alert_bean;

    protected $skip_uniq_validate = false;

    public function saveAsAlert($description = true, $override = array())
    {
        // when abstract - not working
    }
    abstract public function setAssignedUserId($assigned_user_id);
    abstract public function setRelatedBean($related_bean_id, $related_bean_type);
    abstract public function setName($name);
    abstract public function setDescription($description);
    abstract public function setType($type); // Type must be the name of the plugin class

    public function setAlertBean($bean){
        $this->alert_bean = $bean;
        return $this;
    }

    public function isUnique()
    {
        $alert_id = $GLOBALS['db']->getOne($this->buildUniqueQueryChecker());
        return (empty($alert_id));
    }

    public function disableUniqueValidation()
    {
        $this->skip_uniq_validate = true;
        return $this;
    }

    public function setActive()
    {
        global $db;
        $alert_id = $db->getOne($this->buildUniqueQueryChecker());
        $bean = BeanFactory::getBean('Alerts',$alert_id);
        $bean->is_read = 0;
        $bean->save();
        if(class_exists($bean->alert_type)){
            $plugin_name = $bean->alert_type;
            $plugin = new $plugin_name();
            if($plugin->isWebPushableNotification()){
                $plugin->getNewNotification()->setAssignedUserId($bean->assigned_user_id)->setAlertBean($bean)
                ->setRelatedBean($bean->parent_id,$bean->parent_type)->setType($bean->alert_type)
                ->WebPush($plugin->getWebPushDescriptionConfig(),$plugin->getWebPushLinkConfig(),$plugin->getWebPushOverrideConfig());
            }
        }
    }

    protected function buildUniqueQueryChecker()
    {
        return " SELECT id FROM `alerts` " . $this->buildUniqueQueryCheckerWhere();
    }

    protected function buildUniqueQueryCheckerWhere()
    {
        return " WHERE `deleted` = 0 AND (type != 'webpush' OR type IS NULL)
           AND `parent_type` = '{$this->related_bean_type}'
           AND `parent_id` = '{$this->related_bean_id}'
           AND `assigned_user_id` = '{$this->assigned_user_id}'
           AND `alert_type` = '{$this->type}'";
    }
}
