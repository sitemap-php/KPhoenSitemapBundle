<?php

namespace KPhoen\SitemapBundle\Tests\Formatter;

use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Formatter\FormatterInterface;
use KPhoen\SitemapBundle\Formatter\SpacelessFormatter;


class TestableFormatter implements FormatterInterface
{
    public function getSitemapStart()
    {
        return "\tjoe\n";
    }

    public function getSitemapEnd()
    {
        return "\tfoo\n";
    }

    public function formatUrl(Url $url)
    {
        return sprintf("\t%s\n", $url->getLoc());
    }
}

class SpacelessFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testSitemapStart()
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $this->assertEquals('joe', $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $this->assertEquals('foo', $formatter->getSitemapEnd());
    }

    public function testFormatUrl()
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());

        $url = new Url();
        $url->setLoc('http://www.google.fr');

        $this->assertEquals('http://www.google.fr', $formatter->formatUrl($url));
    }
}
