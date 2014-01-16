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
        $this->assertCount(2, $crawler->filterXPath('//default:url'));
    }

    /**
     * @dataProvider urlsProvider
     */
    public function testSitemapUrl($pos, $loc, $changefreq, $priority, $lastmod)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals($loc, $crawler->filterXPath('//default:urlset/default:url')->eq($pos)->filterXPath('//default:loc')->text());

        if ($changefreq === null) {
            $this->assertCount(0, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:changefreq'));
        } else {
            $this->assertEquals($changefreq, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:changefreq')->text());
        }

        if ($priority === null) {
            $this->assertCount(0, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:priority'));
        } else {
            $this->assertEquals($priority, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:priority')->text());
        }

        if ($lastmod === null) {
            $this->assertCount(0, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:lastmod'));
        } else {
            $this->assertEquals($lastmod, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:lastmod')->text());
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
