<?php

namespace TyHand\SimpleApiKeyMongoStorageBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('ty_hand_simple_api_key_mongo_storage')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('manager')
                    ->defaultValue('default')
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
