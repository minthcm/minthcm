<?php
require_once 'include/WebPushNotifications/WebPushNotifiable.php';
abstract class WebPushNotification implements WebPushNotifiable
{
    protected $title;
    protected $body;
    protected $url_redirect;
    protected $user_id;
    protected $bean;
    protected $type; // Type required for settings validation
    protected $related_module;
    protected $related_id;

    public function __construct($bean){
        $this->bean = $bean;
    }

    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getNotificationTitle()
    {
        return $this->title;
    }
    public function getNotificationBody()
    {
        return $this->body;
    }

    public function getRedirectUrl()
    {
        return empty($this->url_redirect)? '': $this->url_redirect;
    }
    
    public function setUrl($url){
        $this->url_redirect = $url;
        return $this;
    }

    public function setType($type){
        $this->type = $type;
        return $this;
    }
    public function push(){
        if($this->canBePushed()){
            $bean = BeanFactory::newBean('Alerts');
            $bean->name = $this->getNotificationTitle();
            $bean->description = $this->getNotificationBody();
            $bean->assigned_user_id = $this->getUserId();
            $bean->is_read = 0;
            $bean->type = "webpush";
            $bean->url_redirect = $this->getRedirectUrl();
            $bean->alert_type = $this->type;
            if(!empty($this->related_module)){
                $bean->parent_type = $this->related_module;
                $bean->parent_id = $this->related_id;
            }

            $bean->save();
        }
    }

    protected function canBePushed(){
        include 'include/WebPushNotifications/notify_config.php';
        if(in_array($this->type,$allow_alerts_from)){
            return true;
        }
        return false;
    }

}