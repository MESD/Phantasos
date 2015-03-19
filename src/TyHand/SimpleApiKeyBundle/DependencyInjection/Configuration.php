<?php

namespace TyHand\SimpleApiKeyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Config class
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Get the configuration tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tyhand.simple_apikey')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('storage_service')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('key_name')
                    ->defaultValue('apikey')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
