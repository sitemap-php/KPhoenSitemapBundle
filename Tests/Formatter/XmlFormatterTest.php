<?php

namespace KPhoen\SitemapBundle\Tests\Formatter;

use KPhoen\SitemapBundle\Entity\Image;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Entity\Video;
use KPhoen\SitemapBundle\Entity\SitemapIndex;
use KPhoen\SitemapBundle\Formatter\XmlFormatter;


class TestableXmlFormatter extends XmlFormatter
{
    public function testFormatVideo(Video $video)
    {
        return $this->formatVideo($video);
    }
}


class XmlFormatterTest extends \PHPUnit_Framework_TestCase
{
    protected $formatter;

    protected function setUp()
    {
        $this->formatter = new XmlFormatter();
    }

    public function testSitemapStart()
    {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n", $this->formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $this->assertEquals('</urlset>', $this->formatter->getSitemapEnd());
    }

    public function testSitemapIndexStart()
    {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\n".'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n", $this->formatter->getSitemapIndexStart());
    }

    public function testSitemapIndexEnd()
    {
        $this->assertEquals('</sitemapindex>', $this->formatter->getSitemapIndexEnd());
    }

    public function testFormatUrlOnlyLoc()
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"</url>\n", $this->formatter->formatUrl($url));
    }

    public function testFormatUrl()
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"</url>\n", $this->formatter->formatUrl($url));
    }

    public function testFormatUrlWithVideo()
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);

        $video = new Video();
        $video->setThumbnailLoc('http://www.example.com/thumbs/123.jpg');
        $video->setTitle('Grilling steaks for summer');
        $video->setDescription('Alkis shows you how to get perfectly done steaks every time');
        $video->setContentLoc('http://www.example.com/video123.flv');
        $video->setPlayerLoc('http://www.example.com/videoplayer.swf?video=123', true, 'ap=1');
        $video->setDuration(600);

        $url->addVideo($video);

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"\t<video:video>\n".
"\t\t<video:title>Grilling steaks for summer</video:title>\n".
"\t\t<video:description>Alkis shows you how to get perfectly done steaks every time</video:description>\n".
"\t\t<video:thumbnail_loc>http://www.example.com/thumbs/123.jpg</video:thumbnail_loc>\n".
"\t\t<video:content_loc>http://www.example.com/video123.flv</video:content_loc>\n".
"\t\t<video:player_loc allow_embed=\"yes\" autoplay=\"ap=1\">http://www.example.com/videoplayer.swf?video=123</video:player_loc>\n".
"\t\t<video:duration>600</video:duration>\n".
"\t</video:video>\n".
"</url>\n", $this->formatter->formatUrl($url));
    }

    public function testFormatUrlWithImage()
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);

        $image = new Image();
        $image->setLoc('http://www.example.com/thumbs/123.jpg');
        $image->setTitle('Grilling steaks for summer');

        $url->addImage($image);

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"\t<image:image>\n".
"\t\t<image:loc>http://www.example.com/thumbs/123.jpg</image:loc>\n".
"\t\t<image:title>Grilling steaks for summer</image:title>\n".
"\t</image:image>\n".
"</url>\n", $this->formatter->formatUrl($url));
    }

    public function testFormatUrlWithVideos()
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);

        $video = new Video();
        $video->setThumbnailLoc('http://www.example.com/thumbs/123.jpg');
        $video->setTitle('Grilling steaks for summer');
        $video->setDescription('Alkis shows you how to get perfectly done steaks every time');
        $url->addVideo($video);

        $video = new Video();
        $video->setThumbnailLoc('http://www.example.com/thumbs/456.jpg');
        $video->setTitle('Grilling steaks for summer - 2');
        $video->setDescription('Alkis shows you how to get perfectly done steaks every time - 2');
        $url->addVideo($video);

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"\t<video:video>\n".
"\t\t<video:title>Grilling steaks for summer</video:title>\n".
"\t\t<video:description>Alkis shows you how to get perfectly done steaks every time</video:description>\n".
"\t\t<video:thumbnail_loc>http://www.example.com/thumbs/123.jpg</video:thumbnail_loc>\n".
"\t</video:video>\n".
"\t<video:video>\n".
"\t\t<video:title>Grilling steaks for summer - 2</video:title>\n".
"\t\t<video:description>Alkis shows you how to get perfectly done steaks every time - 2</video:description>\n".
"\t\t<video:thumbnail_loc>http://www.example.com/thumbs/456.jpg</video:thumbnail_loc>\n".
"\t</video:video>\n".
"</url>\n", $this->formatter->formatUrl($url));
    }

    public function testFormatFullVideo()
    {
        $formatter = new TestableXmlFormatter();

        $video = new Video();
        $video->setThumbnailLoc('http://www.example.com/thumbs/123.jpg');
        $video->setTitle('Grilling steaks for summer');
        $video->setDescription('Alkis shows you how to get perfectly done steaks every time');
        $video->setContentLoc('http://www.example.com/video123.flv');
        $video->setPlayerLoc('http://www.example.com/videoplayer.swf?video=123', true, 'ap=1');
        $video->setDuration(600);
        $video->setExpirationDate('2012-12-23');
        $video->setRating(2.2);
        $video->setViewCount(42);
        $video->setFamilyFriendly(false);
        $video->setTags(array('test', 'video'));
        $video->setCategory('test category');
        $video->setRestrictions(array('fr', 'us'), Video::RESTRICTION_DENY);
        $video->setGalleryLoc('http://www.example.com/gallery/foo', 'Foo gallery');
        $video->setRequiresSubscription(true);
        $video->setUploader('K-Phoen', 'http://www.kevingomez.fr');
        $video->setPlatforms(array(
            Video::PLATFORM_TV  => Video::RESTRICTION_ALLOW,
            Video::PLATFORM_WEB => Video::RESTRICTION_ALLOW,
        ));
        $video->setLive(false);

        $this->assertEquals(
"\t<video:video>\n".
"\t\t<video:title>Grilling steaks for summer</video:title>\n".
"\t\t<video:description>Alkis shows you how to get perfectly done steaks every time</video:description>\n".
"\t\t<video:thumbnail_loc>http://www.example.com/thumbs/123.jpg</video:thumbnail_loc>\n".
"\t\t<video:content_loc>http://www.example.com/video123.flv</video:content_loc>\n".
"\t\t<video:player_loc allow_embed=\"yes\" autoplay=\"ap=1\">http://www.example.com/videoplayer.swf?video=123</video:player_loc>\n".
"\t\t<video:duration>600</video:duration>\n".
sprintf("\t\t<video:expiration_date>%s</video:expiration_date>\n", $this->dateFormatW3C('2012-12-23')).
"\t\t<video:rating>2.2</video:rating>\n".
"\t\t<video:view_count>42</video:view_count>\n".
"\t\t<video:family_friendly>no</video:family_friendly>\n".
"\t\t<video:tag>test</video:tag>\n".
"\t\t<video:tag>video</video:tag>\n".
"\t\t<video:category>test category</video:category>\n".
"\t\t<video:restriction relationship=\"deny\">fr us</video:restriction>\n".
"\t\t<video:gallery_loc title=\"Foo gallery\">http://www.example.com/gallery/foo</video:gallery_loc>\n".
"\t\t<video:requires_subscription>yes</video:requires_subscription>\n".
"\t\t<video:uploader info=\"http://www.kevingomez.fr\">K-Phoen</video:uploader>\n".
"\t\t<video:platform relationship=\"allow\">tv</video:platform>\n".
"\t\t<video:platform relationship=\"allow\">web</video:platform>\n".
"\t\t<video:live>no</video:live>\n".
"\t</video:video>\n", $formatter->testFormatVideo($video));
    }

    public function testFormatUrlWithFullImage()
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr/?s=joe"');
        $url->setPriority(0.2);
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);

        $image = new Image();
        $image->setLoc('http://www.example.com/thumbs/123.jpg');
        $image->setTitle('Grilling steaks for "summer"');
        $image->setCaption('Some caption');
        $image->setLicense('http://opensource.org/licenses/mit-license.php');
        $image->setGeoLocation('France');

        $url->addImage($image);

        $this->assertEquals("<url>\n".
"\t<loc>http://www.google.fr/?s=joe&quot;</loc>\n".
"\t<changefreq>never</changefreq>\n".
"\t<priority>0.2</priority>\n".
"\t<image:image>\n".
"\t\t<image:loc>http://www.example.com/thumbs/123.jpg</image:loc>\n".
"\t\t<image:caption>Some caption</image:caption>\n".
"\t\t<image:geo_location>France</image:geo_location>\n".
"\t\t<image:title>Grilling steaks for &quot;summer&quot;</image:title>\n".
"\t\t<image:license>http://opensource.org/licenses/mit-license.php</image:license>\n".
"\t</image:image>\n".
"</url>\n", $this->formatter->formatUrl($url));
    }

    public function testFormatSitemapIndex()
    {
        $sitemapIndex = new SitemapIndex();
        $sitemapIndex->setLoc('http://www.example.com/sitemap-1.xml');
        $sitemapIndex->setLastMod(new \DateTime('2013-07-26 23:42:00'));

        $this->assertEquals("<sitemap>\n".
"\t<loc>http://www.example.com/sitemap-1.xml</loc>\n".
"\t<lastmod>2013-07-26T23:42:00+02:00</lastmod>\n".
"</sitemap>\n", $this->formatter->formatSitemapIndex($sitemapIndex));
    }


    protected function dateFormatW3C($date)
    {
        $date = new \DateTime($date);
        return $date->format(\DateTime::W3C);
    }
}
