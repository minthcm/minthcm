<?php
namespace Api\V8\Service;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\JsonApi\Response\DocumentResponse;
use Api\V8\JsonApi\Response\MetaResponse;

#[\AllowDynamicProperties]
class LogoutService
{
    /**
     * @var BeanManager
     */
    protected $beanManager;

    /**
     * @param BeanManager $beanManager
     */
    public function __construct(BeanManager $beanManager)
    {
        $this->beanManager = $beanManager;
    }

    /**
     * @param string $accessToken
     *
     * @return DocumentResponse
     * @throws \InvalidArgumentException When access token is not found.
     */
    public function logout($accessToken, $request) // MintHCM #136592
    {
        // same logic in Access and Refresh token repository, refactor this later
        $token = $this->beanManager->newBeanSafe(\OAuth2Tokens::class);
        $token->retrieve_by_string_fields(
            ['access_token' => $accessToken]
        );

        if ($token->id === null) {
            throw new \InvalidArgumentException('Access token is not found for this client');
        }

        $token->mark_deleted($token->id);

        // MintHCM #136592 start
        $device_id = $request->getParam('device_id');
        $user_name = $request->getParam('user_name');
        $user_bean = $this->beanManager->newBeanSafe('Users'); /** @var User $user_bean */
        $user_bean->retrieve_by_string_fields(['user_name' => $user_name]);
        if (!empty($user_bean->id)) {
            $app_tokens_array = json_decode(html_entity_decode($user_bean->app_tokens), 1);
            if (!empty($app_tokens_array[$device_id])) {
                unset($app_tokens_array[$device_id]);
                $user_bean->app_tokens = json_encode($app_tokens_array);
                $user_bean->skip_vt_validation = true;
                $user_bean->save();
            }
        }
        // MintHCM #136592 end

        $response = new DocumentResponse();
        $response->setMeta(
            new MetaResponse(['message' => 'You have been successfully logged out'])
        );

        return $response;
    }
}
