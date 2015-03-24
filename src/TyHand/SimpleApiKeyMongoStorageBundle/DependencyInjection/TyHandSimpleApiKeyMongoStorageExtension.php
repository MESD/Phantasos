<?php

namespace TyHand\SimpleApiKeyMongoStorageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * Extension class
 */
class TyHandSimpleApiKeyMongoStorageExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @see Extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load the configs
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration( $configuration, $configs );
        $loader = new YamlFileLoader($container, new FileLocator( __DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Put the parameter values into the container
        $container->setParameter('tyhand.simple_apikey_mongo.manager_name', $config['manager']);
    }

    /**
     * @see Extension
     */
    public function prepend(ContainerBuilder $container)
    {
        // Get a list of the bundles
        $bundles = $container->getParameter('kernel.bundles');

        // Check if the simple api key bundle is happily present
        if (isset($bundles['TyHandSimpleApiKeyBundle'])) {
            $config = array(
                'storage_service' => 'tyhand.simple_apikey_mongo_storage.storage'
            );
            foreach($container->getExtensions() as $name => $extension) {
                switch($name) {
                    case 'ty_hand_simple_api_key':
                        $container->prependExtensionConfig($name, $config);
                        break;
                }
            }
        }
    }
}
