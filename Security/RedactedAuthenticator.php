<?php

namespace Redacted\PasswordlessBundle\Security;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\VarDumper\VarDumper;

class RedactedAuthenticator
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;


    public function __construct(SecurityContextInterface $context)
    {
        $this->securityContext = $context;
    }

    
}