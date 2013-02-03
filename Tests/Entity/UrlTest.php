<?php

namespace KPhoen\SitemapBundle\Tests\Entity;

use KPhoen\SitemapBundle\Entity\Url;


class UrlTest extends \PHPUnit_Framework_TestCase
{
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
            array('2012-12-20 18:44', null, $this->dateFormatW3C('2012-12-20 18:44')),
            array('2012-12-20 18:44', Url::CHANGEFREQ_HOURLY, $this->dateFormatW3C('2012-12-20 18:44')),
            array('2012-12-20 18:44', Url::CHANGEFREQ_ALWAYS, $this->dateFormatW3C('2012-12-20 18:44')),
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

    protected function dateFormatW3C($date)
    {
        $date = new \DateTime($date);
        return $date->format(\DateTime::W3C);
    }
}
