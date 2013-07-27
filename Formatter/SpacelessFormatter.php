<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Entity\SitemapIndex;


class SpacelessFormatter implements SitemapIndexFormatterInterface
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

    public function getSitemapIndexStart()
    {
        if (!$this->formatter instanceof SitemapIndexFormatterInterface) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexStart());
    }

    public function getSitemapIndexEnd()
    {
        if (!$this->formatter instanceof SitemapIndexFormatterInterface) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexEnd());
    }

    public function formatSitemapIndex(SitemapIndex $sitemapIndex)
    {
        if (!$this->formatter instanceof SitemapIndexFormatterInterface) {
            return '';
        }

        return $this->stripSpaces($this->formatter->formatSitemapIndex($sitemapIndex));
    }

    protected function stripSpaces($string)
    {
        return str_replace(array("\t", "\r", "\n"), '', $string);
    }
}
