<?php

namespace KPhoen\SitemapBundle\Tests\Formatter;

use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Formatter\XmlFormatter;


class XmlFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testSitemapStart()
    {
        $formatter = new XmlFormatter();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n", $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new XmlFormatter();
        $this->assertEquals('</urlset>', $formatter->getSitemapEnd());
    }

    public function testFormatUrlOnlyLoc()
    {
        $formatter = new XmlFormatter();

        $url = new Url();
        $url->setLoc('http://www.google.fr');

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"</url>\n", $formatter->formatUrl($url));
    }

    public function testFormatUrl()
    {
        $formatter = new XmlFormatter();

        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"</url>\n", $formatter->formatUrl($url));
    }
}
