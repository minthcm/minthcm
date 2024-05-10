<?php

namespace Api\V8\OAuth2\Grant;

use League\OAuth2\Server\Grant\PasswordGrant;
use Psr\Http\Message\ServerRequestInterface;  // MintHCM #131001

class MobileGrant extends PasswordGrant
{
    public function getIdentifier()
    {
        return 'mobile';
    }

    // MintHCM #131001 start
    public function canRespondToAccessTokenRequest(ServerRequestInterface $request)
    {
        $requestParameters = (array) $request->getParsedBody();

        return array_key_exists('grant_type', $requestParameters)
        && in_array($requestParameters['grant_type'], ['password', 'mobile'])
        && array_key_exists('client_id', $requestParameters)
        && $requestParameters['client_id'] == 'mobile';
    }
    // MintHCM #131001 end
}
