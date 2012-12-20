<?php

namespace KPhoen\SitemapBundle\Tests\Fixtures\Provider;

use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Provider\ProviderInterface;
use KPhoen\SitemapBundle\Sitemap\Sitemap;


class TestProvider implements ProviderInterface
{
    public function populate(Sitemap $sitemap)
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);
        $url->setLastmod('2012-12-19 02:28');
        $sitemap->add($url);
    }
}
