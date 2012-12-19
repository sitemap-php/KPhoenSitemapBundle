<?php

namespace KPhoen\SitemapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use KPhoen\SitemapBundle\Dumper\MemoryDumper;


class SitemapController extends Controller
{
    public function sitemapAction()
    {
        $sitemap = $this->container->get('sitemap');
        // force the dumper to a memory dumper to be able to return the output
        $sitemap->setDumper(new MemoryDumper());

        return new Response($sitemap->build(), 200, array(
            'Content-Type' => 'application/xml',
        ));
    }
}
