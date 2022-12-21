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
require_once 'include/externalAPI/GoogleEmail/ExtAPIMicrosoftEmail.php';

class EAPMViewMicrosoftOauth2Redirect extends SugarView
{
    use Oauth2RedirectTrait;

    /**
     * @var ExternalAPIBase $api the API object used to communicate with Microsoft
     */
    private $api;

    private $templateFile = 'modules/EAPM/tpls/MicrosoftOauth2Redirect.tpl';

    private $dataSource = 'microsoftEmailRedirect';

    protected function authenticate()
    {
        if (!isset($_REQUEST['code'])) {
            return false;
        }

        $this->api = new ExtAPIMicrosoftEmail();
        return $this->api->authenticate($_REQUEST['code']);
    }

    /**
     * Constructs a basic response object that indicates the success status of
     * the token authentication
     *
     * @param string $token the token received from Microsoft
     * @return array
     */
    protected function buildBasicResponse($token)
    {
        if (empty($token)) {
            return array(
                'result' => false,
                'dataSource' => 'microsoftOauthRedirect',
            );
        }

        // Build a basic response object indicating authentication success
        $response = array(
            'result' => true,
            'hasRefreshToken' => !empty($token->getRefreshToken()),
            'dataSource' => 'microsoftOauthRedirect',
        );

        return $response;
    }
}
