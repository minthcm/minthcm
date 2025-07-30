<?php

namespace Api\V8\OAuth2\Grant;

use BeanFactory; // MintHCM #136592
use League\OAuth2\Server\Entities\ClientEntityInterface; // MintHCM #136592
use League\OAuth2\Server\Grant\PasswordGrant;
use Psr\Http\Message\ServerRequestInterface;  // MintHCM #131001

#[\AllowDynamicProperties]
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

    // MintHCM #136592 start
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $user = parent::validateUser($request, $client);
        $device_id = $this->getRequestParameter('device_id', $request);
        $firebase_token = $this->getRequestParameter('firebase_token', $request);
        if (!empty($device_id) && !empty($firebase_token)) {
            $user_bean = BeanFactory::getBean('Users', $user->getIdentifier()); /** @var User $user_bean */
            $app_tokens_array = json_decode(html_entity_decode($user_bean->app_tokens), 1);
            if (empty($app_tokens_array[$device_id]) || $app_tokens_array[$device_id] != $firebase_token) {
                $app_tokens_array[$device_id] = $firebase_token;
                $user_bean->app_tokens = json_encode($app_tokens_array);
                $user_bean->skip_vt_validation = true;
                $user_bean->save();
            }
        }
        return $user;
    }
    // MintHCM #136592 end
}
