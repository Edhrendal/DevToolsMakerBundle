<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\DependencyInjection;

use Symfony\Component\Config\{
    Definition\Builder\TreeBuilder,
    Definition\ConfigurationInterface
};

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dev_tools_maker');

        $treeBuilder
            ->getRootNode()
            ->children()
            ->scalarNode('root_namespace')
            ->defaultValue('App')
            ->end()
            ->end();

        return $treeBuilder;
    }
}
