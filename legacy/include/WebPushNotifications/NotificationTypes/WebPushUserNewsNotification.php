<?php
require_once 'include/WebPushNotifications/WebPushNotification.php';
class WebPushUserNewsNotification extends WebPushNotification
{
    public function __construct(UsersNews $bean)
    {
        parent::__construct($bean);
        $this->title = $bean->news_name;
        $this->body = '';
        $this->user_id = $bean->assigned_user_id;

        $this->url_redirect = 'index.php?module=UsersNews&action=DetailView&record=' . $bean->id;
    }

    
}