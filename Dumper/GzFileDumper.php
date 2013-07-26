<?php

namespace KPhoen\SitemapBundle\Dumper;


/**
 * Dump the sitemap into a file and compress it.
 *
 * @see KPhoen\SitemapBundle\Dumper\FileDumper
 */
class GzFileDumper implements DumperFileInterface
{
    protected $filename = null;
    protected $handle = null;


    /**
     * Constructor.
     *
     * @param string $filename The filename. Must be acessible in write mode.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilename($filename)
    {
        $this->clearHandle();
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function clearHandle()
    {
        if ($this->handle !== null) {
            gzclose($this->handle);
            $this->handle = null;
        }
    }

    /**
     * {@inheritdoc}
     */
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
        $this->clearHandle();
    }
}
