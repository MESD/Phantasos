<?php

namespace TyHand\SimpleApiKeyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use TyHand\SimpleApiKeyBundle\DependencyInjection\Compiler\KeyGeneratorCompilerPass;

class TyHandSimpleApiKeyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new KeyGeneratorCompilerPass());
    }
}
