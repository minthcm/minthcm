<?php
namespace Api\V8\OAuth2\Repository;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\OAuth2\Entity\UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var BeanManager
     */
    private $beanManager;

    /**
     * @param BeanManager $beanManager
     */
    public function __construct(BeanManager $beanManager)
    {
        $this->beanManager = $beanManager;
    }

    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException If user does not exist or the password is invalid.
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {

        if($this->IsLdapOn() && !(new \AuthenticationController())->authController->loginAuthenticate($username, $password, false, [])){
            throw new \InvalidArgumentException("The password is invalid: {$password} or username is invalid: {$username}");   
        }

        /** @var \User $user */
        $user = $this->beanManager->newBeanSafe('Users');
        $user->retrieve_by_string_fields(
            ['user_name' => $username]
        );

        if ($user->id === null) {
            throw new \InvalidArgumentException('No user found with this username: ' . $username);
        }

        if (!\User::checkPassword($password, $user->user_hash)&& !$this->IsLdapOn()) {
            throw new \InvalidArgumentException('The password is invalid: ' . $password);
        }

        return new UserEntity($user->id);
    }
    protected function IsLdapOn(){
        return !empty($GLOBALS['system_config']->settings['system_ldap_enabled']) && $GLOBALS['system_config']->settings['system_ldap_enabled'] == true;
    }
}
