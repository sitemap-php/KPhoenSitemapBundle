<?php

namespace KPhoen\SitemapBundle\Dumper;


/*
 * The dumper takes care of the sitemap's persistance (file, compressed file,
 * memory) and the formatter formats it.
 */
interface DumperInterface
{
    /**
     * Dump a string.
     *
     * @param string $string The string to dump.
     */
    public function dump($string);
}
