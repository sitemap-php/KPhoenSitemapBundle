<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Url;


interface FormatterInterface
{
    public function getSitemapStart();
    public function getSitemapEnd();
    public function formatUrl(Url $url);
}
