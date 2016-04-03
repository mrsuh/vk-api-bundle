<?php

namespace Mrsuh\VkApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MrsuhVkApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('mrsuh_vk_api.app_id', $config['app_id']);
        $container->setParameter('mrsuh_vk_api.username', $config['username']);
        $container->setParameter('mrsuh_vk_api.password', $config['password']);
        $container->setParameter('mrsuh_vk_api.scope', $config['scope']);
        $container->setParameter('mrsuh_vk_api.version', $config['version']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('parameters.yml');
    }
}
