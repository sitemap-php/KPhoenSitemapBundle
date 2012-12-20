<?php

namespace KPhoen\SitemapBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SitemapControllerTest extends WebTestCase
{
    public function testSitemapNbUrls()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(2, $crawler->filter('url'));
    }

    /**
     * @dataProvider urlsProvider
     */
    public function testSitemapUrl($pos, $loc, $changefreq, $priority, $lastmod)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals($loc, $crawler->filter('url')->eq($pos)->filter('loc')->text());

        if ($changefreq === null) {
            $this->assertCount(0, $crawler->filter('url')->eq($pos)->filter('changefreq'));
        } else {
            $this->assertEquals($changefreq, $crawler->filter('url')->eq($pos)->filter('changefreq')->text());
        }

        if ($priority === null) {
            $this->assertCount(0, $crawler->filter('url')->eq($pos)->filter('priority'));
        } else {
            $this->assertEquals($priority, $crawler->filter('url')->eq($pos)->filter('priority')->text());
        }

        if ($lastmod === null) {
            $this->assertCount(0, $crawler->filter('url')->eq($pos)->filter('lastmod'));
        } else {
            $this->assertEquals($lastmod, $crawler->filter('url')->eq($pos)->filter('lastmod')->text());
        }
    }

    public function urlsProvider()
    {
        return array(
            // (pos, loc, changefreq, priority, lastmod)
            array(0, 'http://www.google.fr', 'never', null, '2012-12-19'), // changefreq is "never", so the time is skipped
            array(1, 'http://github.com', 'always', 0.2, null),
        );
    }
}
