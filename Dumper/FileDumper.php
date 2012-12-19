<?php

namespace KPhoen\SitemapBundle\Dumper;


class FileDumper implements DumperInterface
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

        fwrite($this->handle, $string);
    }

    protected function openFile()
    {
        $this->handle = fopen($this->filename, 'w');

        if ($this->handle === false) {
            throw new \RuntimeException(sprintf('Impossible to open the file %s in write mode', $this->filename));
        }
    }

    public function __destruct()
    {
        if ($this->handle !== null) {
            fclose($this->handle);
        }
    }
}
