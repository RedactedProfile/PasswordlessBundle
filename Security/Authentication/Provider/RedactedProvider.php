<?php

namespace Redacted\PasswordlessBundle\Security\Authentication\Provider;

use Redacted\PasswordlessBundle\Security\Authentication\Token\RedactedUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RedactedProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    function __construct(UserProviderInterface $userProviderInterface)
    {
        $this->userProvider = $userProviderInterface;
    }


    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if($user)
        {
            $authenticationToken = new RedactedUserToken($user->getRoles());
            $authenticationToken->setUser($user);

            return $authenticationToken;
        }

        throw new AuthenticationException('The Redacted authentication failed');
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof RedactedUserToken;
    }

}