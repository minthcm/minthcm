<?php

namespace Api\V8\OAuth2\Grant;

use League\OAuth2\Server\Grant\PasswordGrant;

class MobileGrant extends PasswordGrant
{
    public function getIdentifier()
    {
        return 'mobile';
    }
}
