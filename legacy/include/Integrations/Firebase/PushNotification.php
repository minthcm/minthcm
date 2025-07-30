<?php

namespace MintHCM\Firebase;

require_once 'vendor/google/apiclient-services/autoload.php';

use Google_Client;
use Google_Exception;

abstract class PushNotification
{
    private $config;

    private const FIREBASE_API_SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';

    public function __construct()
    {
        $this->config = new Config();
    }

    abstract public function execute($data): bool;

    public function sendNotification($title, $to, $body = '', $image = '', $link = ''): bool
    {
        try {
            if (is_array($to)) {
                foreach ($to as $token) {
                    $this->sendNotificationToDevice($title, $token, $body, $image, $link);
                }
            } elseif (is_string($to)) {
                $this->sendNotificationToDevice($title, $to, $body, $image, $link);
            } else {
                throw new \Exception('Missing device token');
            }
        } catch (\Throwable $e) {
            $GLOBALS['log']->fatal('[Firebase Push Notifications] Error:' . $e->getMessage());
            return false;
        }
        return true;
    }

    private function sendNotificationToDevice($title, $device_token, $body = '', $image = '', $link = ''): void
    {
        $payload = [
            "message" => [
                "token" => $device_token,
                "notification" => [
                    'title' => html_entity_decode(htmlspecialchars_decode($title), ENT_QUOTES),
                    'body' => html_entity_decode(htmlspecialchars_decode($body), ENT_QUOTES),
                ],
                "android" => [
                    'priority' => $this->config->get('priority'),
                    "notification" => [
                        "sound" => $this->config->get('sound'),
                        'channel_id' => $this->config->get('android_channel_id'),
                    ],
                ],
                "apns" => [
                    "payload" => [
                        "aps" => [
                            "sound" => $this->config->get('sound'),
                            'channel_id' => $this->config->get('android_channel_id'),
                            'badge' => 1,
                        ],
                    ],
                ],
            ],
        ];
        if (!empty($image)) {
            $payload['message']['android']['notification']['image'] = $image;
            $payload['message']['apns']['fcm_options']['image'] = $image;
        }
        if (!empty($link)) {
            $payload['message']['android']['data']['link'] = $link;
            $payload['message']['apns']['payload']['link'] = $link;
        }

        $post_data = json_encode($payload);
        $access_token = $this->getOATHToken();
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json' . "\r\nAuthorization: Bearer $access_token",
                'content' => $post_data,
            ],
        ];

        $context = stream_context_create($opts);
        $result = file_get_contents('https://fcm.googleapis.com/v1/projects/' . $this->config->get('project_id') . '/messages:send', false, $context);

        if ($result === false) {
            $GLOBALS['log']->fatal('[Firebase Push Notifications] Error: sending notification failed - no response. Token: ' . substr($device_token, 0, 10) . ' ...');
            return;
        }
        $json = json_decode($result);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $GLOBALS['log']->fatal('[Firebase Push Notifications] Error: ' . json_last_error_msg() . '. Token: ' . substr($device_token, 0, 10) . ' ...');
            return;
        }
        if (empty($json->name)) {
            $GLOBALS['log']->fatal('[Firebase Push Notifications] Error: sending notification failed - json');
        }
    }

    private function getOATHToken()
    {
        $client = new Google_Client();
        try {
            $client->setAuthConfig(
                [
                    'type' => $this->config->get('type'),
                    'project_id' => $this->config->get('project_id'),
                    'private_key_id' => $this->config->get('private_key_id'),
                    'private_key' => $this->config->get('private_key'),
                    'client_email' => $this->config->get('client_email'),
                    'client_id' => $this->config->get('client_id'),
                    'auth_uri' => $this->config->get('auth_uri'),
                    'token_uri' => $this->config->get('token_uri'),
                    'auth_provider_x509_cert_url' => $this->config->get('auth_provider_x509_cert_url'),
                    'client_x509_cert_url' => $this->config->get('client_x509_cert_url'),
                    'universe_domain' => $this->config->get('universe_domain'),
                ]
            );
            $client->addScope(self::FIREBASE_API_SCOPE);

            $savedTokenJson = $this->readSavedToken();

            if ($savedTokenJson) {
                // the token exists, set it to the client and check if it's still valid
                $client->setAccessToken($savedTokenJson);
                $accessToken = $savedTokenJson;
                if ($client->isAccessTokenExpired()) {
                    // the token is expired, generate a new token and set it to the client
                    $accessToken = $this->generateToken($client);
                    $client->setAccessToken($accessToken);
                }
            } else {
                // the token doesn't exist, generate a new token and set it to the client
                $accessToken = $this->generateToken($client);
                $client->setAccessToken($accessToken);
            }

            return $accessToken["access_token"];

        } catch (Google_Exception $e) {}
        return false;
    }

    private function readSavedToken()
    {
        $tk = @file_get_contents(__DIR__ . '/Cache/token.cache');
        if ($tk) {
            return json_decode($tk, true);
        } else {
            return false;
        }

    }
    private function writeToken($tk)
    {
        if (!file_exists(__DIR__ . '/Cache')) {
            mkdir(__DIR__ . '/Cache', 0755);
        }
        file_put_contents(__DIR__ . "/Cache/token.cache", $tk);
    }

    private function generateToken($client)
    {
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();

        $tokenJson = json_encode($accessToken);
        $this->writeToken($tokenJson);

        return $accessToken;
    }

    protected function isFirebaseConfigured(): bool
    {
        if (empty($this->config) || empty($this->config->get('project_id'))) {
            $GLOBALS['log']->debug('[Firebase Push Notifications] Firebase is not configured');
            return false;
        }
        return true;
    }

}
