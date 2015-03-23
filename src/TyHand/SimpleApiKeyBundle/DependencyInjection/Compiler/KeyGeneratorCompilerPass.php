<?php

namespace TyHand\SimpleApiKeyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to handle key generator tags
 *
 * The tag for a key generator is as follows
 *  - { name: tyhand.simple_apikey.keygen alias: keygenName default: false }
 * Where name is the basic name of the tag as is standard, alias is the name
 * that the manager should refer to the generator as, and default is an optional
 * flag on to whether to make this generator the default (if left off, it will
 * be the same as marking it as false)
 *
 * Most of this code was taken from the Symfony tutorial on tagged services
 */
class KeyGeneratorCompilerPass implements CompilerPassInterface
{
    /////////////////////////////////////
    // COMPILER PASS INTERFACE METHODS //
    /////////////////////////////////////

    /**
     * Process the tags
     * @param  ContainerBuilder $container Container builder
     */
    public function process(ContainerBuilder $container)
    {
        // Check if the container has the generator manager set
        if (!$container->hasDefinition(
            'tyhand.simple_apikey.generator_manager'
        )) {
            // No need to process anymore
            return;
        }

        // Get the generator manager definition
        $gmDefinition = $container->getDefinition(
            'tyhand.simple_apikey.generator_manager'
        );

        // Get all the services with the tag
        $taggedServices = $container->findTaggedServiceIds(
            'tyhand.simple_apikey.keygen'
        );

        // Add them to the manager
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                // Determine default status
                if (isset($attributes['default'])) {
                    $default = $attributes['default'];
                } else {
                    $default = false;
                }

                // Add the method call
                $gmDefinition->addMethodCall(
                    'addKeyGenerator',
                    array(new Reference($id), $attributes['alias'], $default)
                );
            }
        }
    }
}
