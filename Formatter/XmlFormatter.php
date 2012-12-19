<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Url;


class XmlFormatter implements FormatterInterface
{
    public function getSitemapStart()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    }

    public function getSitemapEnd()
    {
        return '</urlset>';
    }

    public function formatUrl(Url $url)
    {
        $buffer = '<url>' . "\n";

        $buffer .= "\t" . '<loc>' . $url->getLoc() . '</loc>' . "\n";

        if ($url->getLastmod() !== null) {
            $buffer .= "\t" . '<lastmod>' . $url->getLastmod() .'</lastmod>' . "\n";
        }

        if ($url->getChangefreq() !== null) {
            $buffer .= "\t" . '<changefreq>' . $url->getChangefreq() .'</changefreq>' . "\n";
        }

        if ($url->getPriority() !== null) {
            $buffer .= "\t" . '<priority>' . $url->getPriority() .'</priority>' . "\n";
        }

        return $buffer . '</url>' . "\n";
    }
}
