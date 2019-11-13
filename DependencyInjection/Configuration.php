<?php

namespace KPhoen\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use function method_exists;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('k_phoen_sitemap');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('k_phoen_sitemap');
        }

        $rootNode
            ->children()
                ->scalarNode('base_host')->defaultNull()->end()
            ->end()
            ->children()
                ->scalarNode('base_host_sitemap')->defaultNull()->end()
            ->end()
            ->children()
                ->scalarNode('file')->defaultValue("%kernel.project_dir%/web/sitemap.xml.gz")->end()
            ->end()
            ->children()
                ->scalarNode('limit')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
