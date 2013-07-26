<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\SitemapIndex;


interface SitemapIndexFormatterInterface extends FormatterInterface
{
    public function getSitemapIndexStart();
    public function getSitemapIndexEnd();
    public function formatSitemapIndex(SitemapIndex $sitemapIndex);
}
