<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

require_once 'include/OAuth2Mailing/vendor/autoload.php';
require_once 'include/externalAPI/Base/ExternalAPIBase.php';

use Google\Client;
use Google\Service\Gmail;

/**
 * ExtAPIGoogleEmail
 */
class ExtAPIGoogleEmail extends ExternalAPIBase
{
    public $supportedModules = array('OutboundEmail', 'InboundEmail');
    public $authMethod = 'oauth2';
    public $connector = 'ext_eapm_google';

    public $useAuth = true;
    public $requireAuth = true;

    protected $scopes = array(
        Gmail::MAIL_GOOGLE_COM,
    );

    public $needsUrl = false;
    public $sharingOptions = null;

    const APP_STRING_ERROR_PREFIX = 'ERR_GOOGLE_API_';

    /**
     * Returns the Google Client object used to access Google servers, with
     * configurations set as we need
     *
     * @return Google\Client
     */
    public function getClient()
    {
        return $this->getGoogleClient();
    }

    /**
     * Creates a new instance of the Google client used to talk to Google
     * servers and configures it with the proper settings
     *
     * @return Google\Client
     */
    protected function getGoogleClient()
    {
        $config = $this->getGoogleOauth2Config();

        $client = new Client();
        $client->setClientId($config['properties']['oauth2_client_id']);
        $client->setClientSecret($config['properties']['oauth2_client_secret']);
        $client->setRedirectUri($config['redirect_uri']);
        $client->setState('email');
        $client->setScopes($this->scopes);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        return $client;
    }

    /**
     * Gets the configuration of the Google connector, and sets the correct
     * callback URI for this particular context (email)
     *
     * @return array
     */
    protected function getGoogleOauth2Config()
    {
        $config = array();
        include 'custom/modules/Connectors/connectors/sources/ext/eapm/google/config.php';
        $config['redirect_uri'] = rtrim(SugarConfig::getInstance()->get('site_url'), '/')
            . '/oauth-handler/GoogleOauth2Redirect';

        return $config;
    }

    /**
     * Authenticates a user's authorization code with Google servers. On success,
     * Returns the token information as well as the ID of the EAPM bean created
     * to store the token information.
     *
     * @param string $code the authorization code provided by Google
     * @return array|bool
     */
    public function authenticate($code)
    {
        // Authenticate the authorization code with Google servers
        try {
            $client = $this->getClient();
            $client->fetchAccessTokenWithAuthCode($code);
        } catch (\Exception $e) {
            $GLOBALS['log']->error($e->getMessage());
            return false;
        }

        // If we are successful, save the new token data in the database
        $eapmId = null;
        $token = $client->getAccessToken();
        if ($token) {
            $eapmId = $this->saveToken($token);
        }

        // Return the token and account information
        $emailAddress = $this->getEmailAddress($eapmId);
        return array(
            'token' => json_encode($token),
            'eapmId' => $eapmId,
            'emailAddress' => $emailAddress,
            'userName' => $emailAddress,
        );
    }

    /**
     * Retrieves an access token from the given EAPM bean. If the access token
     * is expired (or close to it), this will automatically refresh it.
     *
     * @param string $eapmId the ID of the EAPM bean storing the access token
     * @return string|bool The access token string if successful; false otherwise
     */
    public function getAccessToken($eapmId)
    {
        $eapmBean = $this->getEAPMBean($eapmId);
        if (!empty($eapmBean->id)) {
            try {
                // Get the current token data we have for the EAPM bean
                $client = $this->getClient();
                $client->setAccessToken(html_entity_decode($eapmBean->api_data));

                // If the token is expired (or close to it), refresh it. Return
                // the access_token portion of the token
                if ($client->isAccessTokenExpired()) {
                    return $this->refreshToken($eapmId);
                } else {
                    $token = $client->getAccessToken();
                    if (isset($token['access_token'])) {
                        return $token['access_token'];
                    }
                }
            } catch (Exception $e) {
                $GLOBALS['log']->error($e->getMessage());
            }
        }
        return false;
    }

    /**
     * Uses a refresh token to refresh the token stored in the given EAPM bean
     *
     * @param string $eapmId the ID of the EAPM bean to save the refreshed token to
     * @return string|bool The new access token string if successful; false otherwise
     */
    protected function refreshToken($eapmId)
    {
        $eapmBean = $this->getEAPMBean($eapmId);
        if (!empty($eapmBean->id)) {
            try {
                // Re-authenticate using the stored refresh token
                $client = $this->getClient();
                $client->setAccessToken(html_entity_decode($eapmBean->api_data));
                $client->refreshToken($client->getRefreshToken());
                $token = $client->getAccessToken();
            } catch (Exception $e) {
                $GLOBALS['log']->error($e->getMessage());
                return false;
            }

            // Save the new access token JSON in the database, and return the
            // access_token portion of it
            if (!empty($token)) {
                $this->saveToken($token, $eapmId);
                return $token['access_token'];
            }
        }
        return false;
    }

    /**
     * Saves a token in the EAPM table. If an EAPM bean ID is provided (and it
     * exists), that row will be updated. Otherwise, will create a new row
     *
     * @param array $accessToken the token information to store
     * @param string|null $eapmId optional: ID of the EAPM record to resave
     * @return string the ID of the EAPM bean saved
     */
    protected function saveToken($accessToken, $eapmId = null)
    {
        $bean = $this->getEAPMBean($eapmId);
        if (empty($bean->id)) {
            $bean->assigned_user_id = null;
            $bean->application = 'Google';
            $bean->validated = true;
        }
        $bean->api_data = json_encode($accessToken);
        $bean->skipReassignment = true;
        return $bean->save();
    }

    /**
     * Contacts the Google servers to revoke the token access for the given EAPM
     * bean ID. On success, also soft-deletes that row of the EAPM table.
     *
     * @param string $eapmId the ID of the EAPM bean to revoke token access for
     * @return bool true iff successful
     */
    public function revokeToken($eapmId)
    {
        $eapmBean = $this->getEAPMBean($eapmId);
        if (!empty($eapmBean->id)) {
            try {
                $client = $this->getClient();
                $client->setAccessToken(html_entity_decode($eapmBean->api_data));
                $client->revokeToken();
            } catch (\Exception $e) {
                return false;
            }

            $eapmBean->mark_deleted($eapmBean->id);
        }

        return true;
    }

    /**
     * Helper function for retrieving an EAPM bean by ID. Encoding is set to
     * false, so JSON formatted token strings will not be encoded. If no bean
     * is found, will return a new EAPM bean
     *
     * @param null|string $eapmId the ID of the EAPM bean to retrieve
     * @return null|SugarBean the retrieved EAPM bean, or a new one if not found
     */
    protected function getEAPMBean($eapmId = null)
    {
        return BeanFactory::getBean('EAPM', $eapmId, false);
    }

    /**
     * Uses an authenticated token to query the Google server to retrieve the
     * Google account's email address
     *
     * @param string $eapmId the ID of the EAPM bean storing the account's Oauth2 token
     * @return string
     */
    public function getEmailAddress($eapmId)
    {
        $eapmBean = $this->getEAPMBean($eapmId);
        if (!empty($eapmBean->id)) {
            try {
                $client = $this->getClient();
                $client->setAccessToken(html_entity_decode($eapmBean->api_data));
                $gmailClient = new Gmail($client);
                return $gmailClient->users->getProfile('me')->emailAddress;
            } catch (Google\Service\Exception $e) {
                $GLOBALS['log']->error($e->getMessage());
            }
        }
        return false;
    }

    public function getPHPMailerOAuth2ProviderClass()
    {
        return League\OAuth2\Client\Provider\Google::class;
    }
}
