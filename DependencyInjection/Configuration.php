<?php

namespace KPhoen\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('k_phoen_sitemap');

        $rootNode
            ->children()
                ->scalarNode('base_host')->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}
