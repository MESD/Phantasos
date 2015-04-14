<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Tags a class as being a processor
 * Services with this tag MUST extend from the
 * Component\Processor\Processors\AbstractProcessor class else this will cause
 * and exception to be thrown
 *
 * Any service with the tag { name: phantasos.tag.processor } will be added to
 * the processor container under the name phantasos.processor_container
 */
class ProcessorCompilerPass implements CompilerPassInterface
{
    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Process the tags
     * @param  ContainerBuilder $container Container Builder
     */
    public function process(ContainerBuilder $container)
    {
        // Check that the container definition exists
        if ($container->hasDefinition('phantasos.processor_container')) {
            $pcDefinition = $container->getDefinition(
                'phantasos.processor_container'
            );
        } else {
            return; // No need for more processing
        }

        // Get all the services with the processor tag
        $taggedServices = $container->findTaggedServiceIds(
            'phantasos.tag.processor'
        );

        // Add each tagged processor to the processor container
        foreach($taggedServices as $id => $tags) {
            // Register the processor with the processor container
            $pcDefinition->addMethodCall(
                'addProcessor',
                array(new Reference($id))
            );
        }
    }
}
