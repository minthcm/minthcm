<?php

namespace MintHCM\Firebase\PushNotifications;

class GeneralNotificationToUser extends \MintHCM\Firebase\PushNotification
{
    public function execute($data = []): bool
    {
        if ($this->isFirebaseConfigured() === false) {
            return false;
        }
        $bean = \BeanFactory::getBean('Users', $data['user_id'] ?? ''); /** @var User $bean */
        $tokens = !empty($bean->id) ? $bean->getTokens() : null;
        if (empty($tokens)) {
            return false;
        }
        return $this->sendNotification(
            $data['title'] ?? '',
            $tokens,
            $data['body'] ?? '',
            '',
            ''
        );
    }
}
