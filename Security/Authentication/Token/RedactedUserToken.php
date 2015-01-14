<?php

namespace Redacted\PasswordlessBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class RedactedUserToken extends AbstractToken
{
    public $providerKey;

    public function __construct($email, $providerKey, array $roles = array())
    {
        parent::__construct($roles);

        if (empty($providerKey)) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }

        $this->setUser($email);
        $this->providerKey = $providerKey;

        $this->setAuthenticated(count($roles)> 0);
    }

    public function getCredentials()
    {
        return '';
    }
}