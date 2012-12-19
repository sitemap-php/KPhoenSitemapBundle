<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Url;


class SpacelessFormatter implements FormatterInterface
{
    protected $formatter;


    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getSitemapStart()
    {
        return $this->stripSpaces($this->formatter->getSitemapStart());
    }

    public function getSitemapEnd()
    {
        return $this->stripSpaces($this->formatter->getSitemapEnd());
    }

    public function formatUrl(Url $url)
    {
        return $this->stripSpaces($this->formatter->formatUrl($url));
    }

    protected function stripSpaces($string)
    {
        return str_replace(array("\t", "\r", "\n"), '', $string);
    }
}
