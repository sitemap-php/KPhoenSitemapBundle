<?php

namespace KPhoen\SitemapBundle\Dumper;


/**
 * Dump a sitemap in memory. Usefull if you don't want to touch your filesystem
 * or if you want to access the sitemap's content.
 */
class MemoryDumper implements DumperInterface
{
    protected $buffer = '';


    /**
     * Dump a string into the buffer.
     *
     * @param string $string The string to dump.
     *
     * @return string The current buffer.
     */
    public function dump($string)
    {
        $this->buffer .= $string;

        return $this->buffer;
    }
}
