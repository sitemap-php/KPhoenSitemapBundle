<?php

namespace KPhoen\SitemapBundle\Tests\Entity;

use KPhoen\SitemapBundle\Entity\Url;


class UrlTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        // avoid test "false negatives" due to the timezone
        ini_set('date.timezone', 'Europe/Paris');
    }

    /**
     * @dataProvider locProvider
     */
    public function testLocEscaping($loc, $escaped_loc)
    {
        $url = new Url();
        $url->setLoc($loc);
        $this->assertEquals($escaped_loc, $url->getLoc());
    }

    /**
     * @expectedException DomainException
     */
    public function testLocMaxLength()
    {
        $url = new Url();
        $url->setLoc('http://google.fr/?q='.str_repeat('o', 2048));
    }

    /**
     * @dataProvider invalidPriorityProvider
     * @expectedException DomainException
     */
    public function testInvalidPriority($priority)
    {
        $url = new Url();
        $url->setPriority($priority);
    }

    /**
     * @expectedException DomainException
     */
    public function testInvalidChangefreq()
    {
        $url = new Url();
        $url->setChangefreq('foo');
    }

    /**
     * @dataProvider changefreqProvider
     */
    public function testChangefreq($changefreq)
    {
        $url = new Url();
        $url->setChangefreq($changefreq);

        $this->assertEquals($changefreq, $url->getChangefreq());
    }

    /**
     * @dataProvider lastmodProvider
     */
    public function testLastmodFormatting($lastmod, $changefreq, $expected_lastmod)
    {
        $url = new Url();
        $url->setLastmod($lastmod);
        $url->setChangefreq($changefreq);

        $this->assertEquals($expected_lastmod, $url->getLastmod());
    }


    public function lastmodProvider()
    {
        return array(
            array(null, null, null),
            array(null, Url::CHANGEFREQ_YEARLY, null),
            array('2012-12-20 18:44', null, '2012-12-20T18:44:00+01:00'),
            array('2012-12-20 18:44', Url::CHANGEFREQ_HOURLY, '2012-12-20T18:44:00+01:00'),
            array('2012-12-20 18:44', Url::CHANGEFREQ_ALWAYS, '2012-12-20T18:44:00+01:00'),
            array('2012-12-20 18:44', Url::CHANGEFREQ_DAILY, '2012-12-20'),
        );
    }

    public function changefreqProvider()
    {
        return array(
            array(null),
            array(Url::CHANGEFREQ_ALWAYS),
            array(Url::CHANGEFREQ_HOURLY),
            array(Url::CHANGEFREQ_DAILY),
            array(Url::CHANGEFREQ_WEEKLY),
            array(Url::CHANGEFREQ_MONTHLY),
            array(Url::CHANGEFREQ_YEARLY),
            array(Url::CHANGEFREQ_NEVER),
        );
    }

    public function invalidPriorityProvider()
    {
        return array(
            array(-0.1),
            array(1.1),
        );
    }

    public function locProvider()
    {
        return array(
            // (loc, escaped loc)
            array('http://www.google.com', 'http://www.google.com'),
            array('http://www.google.com/search=j\'aime les frites', 'http://www.google.com/search=j&#039;aime les frites'),
            array('http://www.google.com/search=joe&foo=bar', 'http://www.google.com/search=joe&amp;foo=bar'),
            array('http://www.google.com/search="hell yeah!"', 'http://www.google.com/search=&quot;hell yeah!&quot;'),
            array('http://www.google.com/search=Linux > Windows', 'http://www.google.com/search=Linux &gt; Windows'),
            array('http://www.google.com/search=Mac < Linux', 'http://www.google.com/search=Mac &lt; Linux'),
        );
    }
}
