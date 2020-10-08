<?php

interface WebPushNotifiable
{
    public function getUserId();
    public function getNotificationTitle();
    public function getNotificationBody();
    public function getRedirectUrl();
    
}