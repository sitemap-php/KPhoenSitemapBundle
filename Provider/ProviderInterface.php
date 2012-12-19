<?php

namespace KPhoen\SitemapBundle\Provider;

use KPhoen\SitemapBundle\Sitemap\Sitemap;


interface ProviderInterface
{
    public function populate(Sitemap $sitemap);
}
