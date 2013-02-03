<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Url;


/**
 * Sitemaps formatted using this class will contain only one URL per line and
 * no other information.
 *
 * @see http://www.sitemaps.org/protocol.html#otherformats
 */
class TextFormatter extends BaseFormatter implements FormatterInterface
{
    public function getSitemapStart()
    {
        return '';
    }

    public function getSitemapEnd()
    {
        return '';
    }

    public function formatUrl(Url $url)
    {
        return $this->escape($url->getLoc()) . "\n";
    }
}
