<?php

namespace KPhoen\SitemapBundle\Dumper;


/*
 * The dumper takes care of the sitemap's persistance (file, compressed file,
 * memory) and the formatter formats it.
 */
interface DumperFileInterface extends DumperInterface
{
    /**
     * Set the filename
     *
     * @param string $filename The filename.
     */
    public function setFilename($filename);

    /**
     * Get the filename
     */
    public function getFilename();

    /**
     * Clear the file handle
     */
    public function clearHandle();
}
