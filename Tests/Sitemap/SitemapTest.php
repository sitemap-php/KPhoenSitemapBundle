<?php

namespace KPhoen\SitemapBundle\Tests\Sitemap;

use KPhoen\SitemapBundle\Dumper\MemoryDumper;
use KPhoen\SitemapBundle\Dumper\FileDumper;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Formatter\TextFormatter;
use KPhoen\SitemapBundle\Provider\ProviderInterface;
use KPhoen\SitemapBundle\Sitemap\Sitemap;


class TestableSitemap extends Sitemap
{
    public function getProviders()
    {
        return $this->providers;
    }

    public function getDumper()
    {
        return $this->dumper;
    }
}

class TestableMemoryDumper extends MemoryDumper
{
    public function getContent()
    {
        return $this->buffer;
    }
}

class TestableProvider implements ProviderInterface
{
    public function populate(Sitemap $sitemap)
    {
        $url = new Url();
        $url->setLoc('/search');
        $sitemap->add($url);
    }
}


class SitemapTest extends \PHPUnit_Framework_TestCase
{
    public function testAddProvider()
    {
        $sitemap = new TestableSitemap(new MemoryDumper(), new TextFormatter());
        $this->assertEquals(0, count($sitemap->getProviders()));

        $sitemap->addProvider(new TestableProvider());
        $this->assertEquals(1, count($sitemap->getProviders()));
    }

    public function testSetDumper()
    {
        $dumper = new MemoryDumper();
        $sitemap = new TestableSitemap($dumper, new TextFormatter());
        $this->assertEquals($dumper, $sitemap->getDumper());

        $other_dumper = new FileDumper('joe');
        $sitemap->setDumper($other_dumper);
        $this->assertEquals($other_dumper, $sitemap->getDumper());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddUrlNoLoc()
    {
        $sitemap = new TestableSitemap(new MemoryDumper(), new TextFormatter(), 'http://www.google.fr');
        $url = new Url();
        $sitemap->add($url);
    }

    public function testAddUrlNoBaseHost()
    {
        $dumper = new TestableMemoryDumper();
        $sitemap = new TestableSitemap($dumper, new TextFormatter(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('/search');

        $sitemap->add($url);

        $this->assertEquals('http://www.google.fr/search', $url->getLoc());
        $this->assertEquals('http://www.google.fr/search' . "\n", $dumper->getContent());
    }

    public function testAddUrlBaseHost()
    {
        $dumper = new TestableMemoryDumper();
        $sitemap = new TestableSitemap($dumper, new TextFormatter(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('http://www.joe.fr/search');

        $sitemap->add($url);

        $this->assertEquals('http://www.joe.fr/search', $url->getLoc());
        $this->assertEquals('http://www.joe.fr/search' . "\n", $dumper->getContent());
    }

    public function testBuild()
    {
        $sitemap = new TestableSitemap(new MemoryDumper(), new TextFormatter(), 'http://www.google.fr');
        $sitemap->addProvider(new TestableProvider());

        $this->assertEquals('http://www.google.fr/search' . "\n", $sitemap->build());
    }
}
