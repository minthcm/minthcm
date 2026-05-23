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

require_once 'modules/EAPM/views/Oauth2RedirectTrait.php';
require_once 'include/externalAPI/GoogleEmail/ExtAPIGoogleEmail.php';

class EAPMViewGoogleOauth2Redirect extends SugarView
{
    use Oauth2RedirectTrait;

    /**
     * @var ExternalAPIBase $api the API object used to communicate with Google
     */
    private $api;

    private $templateFile = 'modules/EAPM/tpls/GoogleOauth2Redirect.tpl';

    private $dataSource = 'googleEmailRedirect';

    /**
     * Authenticates a Google authorization code with Google servers, storing
     * any resulting token information in the EAPM table
     *
     * @return bool|string
     */
    protected function authenticate()
    {
        if (!isset($_GET['code'])) {
            return false;
        }

        $this->api = new ExtAPIGoogleEmail();
        return $this->api->authenticate($_GET['code']);
    }

    /**
     * Constructs a basic response object that indicates the success status of
     * the token authentication
     *
     * @param string $tokenJSON the JSON token string received from Google
     * @return array
     */
    protected function buildBasicResponse($tokenJSON)
    {
        if (empty($tokenJSON)) {
            return array(
                'result' => false,
            );
        }

        // Build a basic response object indicating authentication success
        $token = json_decode($tokenJSON, true);
        $response = array(
            'result' => true,
            'hasRefreshToken' => isset($token['refresh_token']),
            'dataSource' => 'googleOauthRedirect',
        );

        return $response;
    }
}
