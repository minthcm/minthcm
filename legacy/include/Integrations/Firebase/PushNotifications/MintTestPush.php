<?php

namespace MintHCM\Firebase\PushNotifications;

class MintTestPush extends \MintHCM\Firebase\PushNotification
{
    public function execute($data = []): bool
    {
        if ($this->isFirebaseConfigured() === false) {
            echo "Firebase is not configured\n";
            return false;
        }
        $user_id = '1';
        $bean = \BeanFactory::getBean('Users', $user_id); /** @var \User $bean */
        $tokens = !empty($bean->id) ? $bean->getTokens() : null;
        if (empty($tokens)) {
            echo "No tokens found for user {$user_id}\n";
            return false;
        }
        return $this->sendNotification(
            'Title Test',
            $tokens,
            'Mint Test Push body',
            'https://firebase.google.com/static/images/brand-guidelines/logo-vertical.png',
            "minthcm://settingsScreen"
        );
    }
}
