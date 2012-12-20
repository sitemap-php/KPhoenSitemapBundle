<?php

namespace KPhoen\SitemapBundle\Tests\Formatter;

use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Formatter\TextFormatter;


class TextFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testSitemapStart()
    {
        $formatter = new TextFormatter();
        $this->assertEquals('', $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new TextFormatter();
        $this->assertEquals('', $formatter->getSitemapEnd());
    }

    public function testFormatUrl()
    {
        $formatter = new TextFormatter();

        $url = new Url();
        $url->setLoc('http://www.google.fr');

        $this->assertEquals('http://www.google.fr' . "\n", $formatter->formatUrl($url));
    }
}
