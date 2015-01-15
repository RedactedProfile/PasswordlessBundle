<?php

namespace Redacted\PasswordlessBundle\EventListener;

use Redacted\PasswordlessBundle\Security\Authentication\Token\RedactedUserToken;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\VarDumper\VarDumper;

class AuthListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if(!$event->isMasterRequest()) {
            return;
        }

        $context = $this->container->get('security.context');
        $session = $event->getRequest()->getSession();

        if($session->get('passwordless_email')) {

            $token = new RedactedUserToken(array('ROLE_USER'));
            $token->setUser($session->get('passwordless_email'));
            $context->setToken($token);

        }
    }
}