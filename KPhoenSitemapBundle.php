<?php

namespace KPhoen\SitemapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use KPhoen\SitemapBundle\DependencyInjection\Compiler\UrlProviderCompilerPass;


class KPhoenSitemapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new UrlProviderCompilerPass());
    }
}
