<?php

require_once 'include/Notifications/NotificationAbstractClass.php';

class NotificationNull extends NotificationAbstractClass
{
    
    public function saveAsAlert($description = true, $override = array())
    {
        return $this;
    }

    public function setAssignedUserId($assigned_user_id)
    {
        return $this;
    }
    public function setRelatedBean($related_bean_id, $related_bean_type)
    {
        return $this;
    }
    public function setRelatedBeanFromBean($related_bean)
    {
        return $this;
    }

    public function setName($name)
    {
        return $this;
    }

    public function setType($type)
    {
        return $this;
    }

    public function setDescription($description)
    {
        return $this;
    }

    public function WebPush($desc = true,$link = true,$override = array()){
        return $this;
    }

}
