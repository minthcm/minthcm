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

    protected $skip_uniq_validate = false;

    abstract public function saveAsAlert();
    abstract public function setAssignedUserId($assigned_user_id);
    abstract public function setRelatedBean($related_bean_id, $related_bean_type);
    abstract public function setName($name);
    abstract public function setDescription($description);

    public function isUnique()
    {
        $alert_id = $GLOBALS['db']->getOne($this->buildUniqueQueryChecker());
        return (empty($alert_id));
    }

    public function disableUniqueValidation()
    {
        $this->skip_uniq_validate = true;
    }

    public function setActive()
    {
        global $db;
        $db->query("UPDATE `alerts` SET `is_read`=0 " . $this->buildUniqueQueryCheckerWhere());
    }

    protected function buildUniqueQueryChecker()
    {
        return " SELECT id FROM `alerts` " . $this->buildUniqueQueryCheckerWhere();
    }

    protected function buildUniqueQueryCheckerWhere()
    {
        return " WHERE `deleted` = 0
           AND `parent_type` = '{$this->related_bean_type}'
           AND `parent_id` = '{$this->related_bean_id}'
           AND `assigned_user_id` = '{$this->assigned_user_id}'
           AND `alert_type` = 'custom'";
    }
}
