<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$config = array(
    'name' => 'Firebase',
    'properties' => array(
        "type" => "service_account",
        "project_id" => "add project id here",
        "private_key_id" => "add private key id here",
        "private_key" => "add private key here",
        "client_email" => "add client email here",
        "client_id" => "add client id here",
        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
        "token_uri" => "https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url" => "add client_x509_cert_url here",
        "universe_domain" => "googleapis.com",
        'priority' => 'high',
        'android_channel_id' => 'minthcm0',
        'sound' => 'default',
    ),
);
