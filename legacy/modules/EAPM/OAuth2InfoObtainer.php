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

require_once 'include/externalAPI/ExternalAPIFactory.php';
require_once 'include/externalAPI/Base/ExternalAPIBase.php';

class OAuth2InfoObtainer
{
    const CONNECTOR_LABELS = [
        'GoogleEmail' => 'LBL_SMTPTYPE_GOOGLE_OAUTH2',
        'MicrosoftEmail' => 'LBL_SMTPTYPE_MICROSOFT',
    ];

    public function getAuthInfo(string $application): array
    {
        $authWarning = $this->getAuthWarning($application);
        $data = ['auth_warning' => $authWarning];

        $extApi = $this->getExternalApi($application);
        if ($extApi) {
            $client = $extApi->getClient();
            $data['auth_url'] = $client->createAuthUrl();
        }

        return $data;
    }

    protected function getAuthWarning(string $application): string
    {
        $connectorName = translate(self::CONNECTOR_LABELS[$application] ?? '');
        return string_format(translate('LBL_EMAIL_AUTH_WARNING'), [$connectorName]);
    }

    protected function getExternalApi(string $application)
    {
        return ExternalAPIFactory::loadAPI($application, true);
    }
}

