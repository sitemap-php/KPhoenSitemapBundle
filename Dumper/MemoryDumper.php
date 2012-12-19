<?php

namespace KPhoen\SitemapBundle\Dumper;


class MemoryDumper implements DumperInterface
{
    protected $buffer = '';


    public function dump($string)
    {
        $this->buffer .= $string;

        return $this->buffer;
    }
}
