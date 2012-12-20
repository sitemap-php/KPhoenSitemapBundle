<?php

namespace KPhoen\SitemapBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SitemapControllerTest extends WebTestCase
{
    public function testSitemap()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
