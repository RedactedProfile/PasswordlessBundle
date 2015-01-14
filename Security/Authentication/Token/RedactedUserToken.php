<?php

namespace Redacted\PasswordlessBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class RedactedUserToken extends AbstractToken
{
    public $providerKey;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        $this->setAuthenticated(count($roles)> 0);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(
            array(
                is_object($this->getUser()) ? clone $this->getUser() : $this->getUser(),
                $this->isAuthenticated(),
                $this->getRoles(),
                $this->getAttributes(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->user, $this->authenticated, $this->roles, $this->attributes) = unserialize($serialized);
    }

    public function getCredentials()
    {
        return '';
    }
}