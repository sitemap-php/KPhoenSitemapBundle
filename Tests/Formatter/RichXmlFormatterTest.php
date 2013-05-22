<?php

namespace KPhoen\SitemapBundle\Tests\Formatter;

use KPhoen\SitemapBundle\Entity\Image;
use KPhoen\SitemapBundle\Entity\RichUrl;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Entity\Video;
use KPhoen\SitemapBundle\Formatter\RichXmlFormatter;


class RichXmlFormatterTest extends XmlFormatterTest
{
    protected function setUp()
    {
        $this->formatter = new RichXmlFormatter();
    }

    public function testSitemapStart()
    {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n", $this->formatter->getSitemapStart());
    }

    public function testFormatRichUrl()
    {
        $url = new RichUrl();
        $url->setLoc('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);
        $url->addAlternateUrl('en', 'http://www.google.com');
        $url->addAlternateUrl('es-es', 'http://www.google.es');

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"\t<xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"http://www.google.com\" />\n".
"\t<xhtml:link rel=\"alternate\" hreflang=\"es-es\" href=\"http://www.google.es\" />\n".
"</url>\n", $this->formatter->formatUrl($url));
    }
}
