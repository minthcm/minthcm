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

trait Oauth2RedirectTrait
{
    /**
     * {@inheritDoc}
     *
     * @param array $params Ignored
     */
    public function process($params = array())
    {
        global $sugar_config;

        $tokenData = $this->authenticate();
        $response = $this->buildResponse($tokenData);

        $this->ss->assign('response', $response);
        $this->ss->assign('siteUrl', $sugar_config['site_url']);
        $this->ss->display($this->templateFile);
    }

    /**
     * Constructs a response object that includes additional information about
     * the EAPM bean created
     *
     * @param $authResult
     * @return array
     */
    protected function buildResponse($authResult): array
    {
        $response = $this->buildBasicResponse($authResult['token'] ?? null);
        $response['dataSource'] = $this->dataSource;
        if (!empty($response['result'])) {
            $response['eapmId'] = $authResult['eapmId'] ?? null;
            $response['emailAddress'] = $authResult['emailAddress'] ?? null;
            $response['userName'] = $authResult['userName'] ?? null;
        }
        return $response;
    }
}
