<?php

namespace Redacted\PasswordlessBundle\Security\Firewall;

use Redacted\PasswordlessBundle\Security\Authentication\Token\RedactedUserToken;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\VarDumper\VarDumper;

class RedactedListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;

    function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }


    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        VarDumper::dump($request);
        die();

        $token = new RedactedUserToken();
        //$token->setUser()

    }
}