<?php
require_once 'include/WebPushNotifications/WebPushNotification.php';
class WebPushBeanNotification extends WebPushNotification
{
    public function __construct(SugarBean $bean)
    {
        parent::__construct($bean);
        $this->title = $bean->name;
        $this->body = $bean->description;
        $this->user_id = $bean->assigned_user_id;
        $this->url_redirect = '';

        if($bean instanceof Alert){
            $this->related_module = $bean->parent_type;
            $this->related_id = $bean->parent_id;
        }
    }

}