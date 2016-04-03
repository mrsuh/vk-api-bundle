<?php

namespace Mrsuh\VkApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mrsuh_vk_api')
            ->children()
            ->variableNode('app_id')->cannotBeEmpty()->end()
            ->variableNode('username')->cannotBeEmpty()->end()
            ->variableNode('password')->cannotBeEmpty()->end()
            ->variableNode('scope')->cannotBeEmpty()->end()
            ->variableNode('version')->cannotBeEmpty()->end();

        return $treeBuilder;
    }
}
