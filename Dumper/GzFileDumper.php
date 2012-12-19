<?php

namespace KPhoen\SitemapBundle\Dumper;


class GzFileDumper implements DumperInterface
{
    protected $filename = null;
    protected $handle = null;


    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function dump($string)
    {
        if ($this->handle == null) {
            $this->openFile();
        }

        gzwrite($this->handle, $string);
    }

    protected function openFile()
    {
        $this->handle = gzopen($this->filename, 'w9');

        if ($this->handle === false) {
            throw new \RuntimeException(sprintf('Impossible to open the file %s in write mode', $this->filename));
        }
    }

    public function __destruct()
    {
        if ($this->handle !== null) {
            gzclose($this->handle);
        }
    }
}

