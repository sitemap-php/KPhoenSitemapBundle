<?php

namespace KPhoen\SitemapBundle\Tests\Sitemap;

use KPhoen\SitemapBundle\Dumper\MemoryDumper;
use KPhoen\SitemapBundle\Dumper\FileDumper;
use KPhoen\SitemapBundle\Entity\Image;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Entity\Video;
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

    public function testAddUrlBaseHostToImages()
    {
        $dumper = new TestableMemoryDumper();
        $sitemap = new TestableSitemap($dumper, new TextFormatter(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('http://www.joe.fr/search');

        $image = new Image();
        $image->setLoc('/thumbs/123.jpg');
        $image->setLicense('/lic/MIT.txt');

        $url->addImage($image);

        $sitemap->add($url);

        $this->assertEquals('http://www.google.fr/thumbs/123.jpg', $image->getLoc());
        $this->assertEquals('http://www.google.fr/lic/MIT.txt', $image->getLicense());
    }

    public function testAddUrlBaseHostToVideos()
    {
        $dumper = new TestableMemoryDumper();
        $sitemap = new TestableSitemap($dumper, new TextFormatter(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('http://www.joe.fr/search');

        $video = new Video();
        $video->setThumbnailLoc('/thumbs/123.jpg');
        $video->setContentLoc('/content/123.avi');
        $video->setPlayerLoc('/player/123.swf');
        $video->setGalleryLoc('/gallery/123');
        $url->addVideo($video);

        $sitemap->add($url);

        $this->assertEquals('http://www.google.fr/thumbs/123.jpg', $video->getThumbnailLoc());
        $this->assertEquals('http://www.google.fr/content/123.avi', $video->getContentLoc());
        $player =  $video->getPlayerLoc();
        $this->assertEquals('http://www.google.fr/player/123.swf', $player['loc']);
        $gallery =  $video->getGalleryLoc();
        $this->assertEquals('http://www.google.fr/gallery/123', $gallery['loc']);
    }

    public function testBuild()
    {
        $sitemap = new TestableSitemap(new MemoryDumper(), new TextFormatter(), 'http://www.google.fr');
        $sitemap->addProvider(new TestableProvider());

        $this->assertEquals('http://www.google.fr/search' . "\n", $sitemap->build());
    }
}
