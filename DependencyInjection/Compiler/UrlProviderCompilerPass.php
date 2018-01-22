<?php

namespace KPhoen\SitemapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add all the services tagged "sitemap.provider" to the sitemap.
 */
class UrlProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sitemap')) {
            return;
        }

        $definition = $container->getDefinition('sitemap');

        foreach ($container->findTaggedServiceIds('sitemap.provider') as $id => $attributes) {
            $definition->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
