<?php

namespace Redacted\PasswordlessBundle;

use Redacted\PasswordlessBundle\DependencyInjection\Security\Factory\RedactedFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\VarDumper\VarDumper;

class RedactedPasswordlessBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new RedactedFactory());
    }
}
