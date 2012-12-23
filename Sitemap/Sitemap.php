<?php

namespace KPhoen\SitemapBundle\Sitemap;

use KPhoen\SitemapBundle\Dumper\DumperInterface;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Formatter\FormatterInterface;
use KPhoen\SitemapBundle\Provider\ProviderInterface;


/**
 * Sitemap generator.
 *
 * It will use a set of providers to build the sitemap.
 * The dumper takes care of the sitemap's persistance (file, compressed file,
 * memory) and the formatter formats it.
 *
 * The whole process tries to be as memory-efficient as possible, that's why URLs
 * are not stored but dumped immediatly.
 */
class Sitemap
{
    protected $providers = array();
    protected $dumper = null;
    protected $formatter = null;
    protected $base_host = null;


    /**
     * Constructor.
     *
     * @param DumperInterface $dumper The dumper to use.
     * @param FormatterInterface $formatter The formatter to use.
     * @param string $base_host The base URl for all the links (well only be used for relative URLs).
     */
    public function __construct(DumperInterface $dumper, FormatterInterface $formatter, $base_host = null)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;
        $this->base_host = $base_host;
    }

    /**
     * Add a provider to the sitemap.
     *
     * @param ProviderInterface $provider The provider.
     *
     * @return Sitemap The current sitemap (for fluent interface).
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
        return $this;
    }

    /**
     * Overrides the used dumper.
     *
     * @param DumperInterface $dumper The new dumper to use.
     *
     * @return Sitemap The current sitemap (for fluent interface).
     */
    public function setDumper(DumperInterface $dumper)
    {
        $this->dumper = $dumper;
        return $this;
    }

    /**
     * Build the sitemap.
     *
     * @return string The sitemap's content if available.
     */
    public function build()
    {
        $this->dumper->dump($this->formatter->getSitemapStart());

        foreach ($this->providers as $provider) {
            $provider->populate($this);
        }

        return $this->dumper->dump($this->formatter->getSitemapEnd());
    }

    /**
     * Add an entry to the sitemap.
     *
     * @param Url $url The URL to add. If the URL is relative, the base host will be prepended.
     *
     * @return Sitemap The current sitemap (for fluent interface).
     */
    public function add(Url $url)
    {
        $loc = $url->getLoc();
        if (empty($loc)) {
            throw new \InvalidArgumentException('The url MUST have a loc attribute');
        }

        if ($this->base_host !== null) {
            if ($this->needHost($loc)) {
                $url->setLoc($this->base_host.$loc);
            }

            foreach ($url->getVideos() as $video) {
                if ($this->needHost($video->getThumbnailLoc())) {
                    $video->setThumbnailLoc($this->base_host.$video->getThumbnailLoc());
                }

                if ($this->needHost($video->getContentLoc())) {
                    $video->setContentLoc($this->base_host.$video->getContentLoc());
                }

                $player = $video->getPlayerLoc();
                if ($player !== null && $this->needHost($player['loc'])) {
                    $video->setPlayerLoc($this->base_host.$player['loc'], $player['allow_embed'], $player['autoplay']);
                }

                $gallery = $video->getGalleryLoc();
                if ($gallery !== null && $this->needHost($gallery['loc'])) {
                    $video->setGalleryLoc($this->base_host.$gallery['loc'], $gallery['title']);
                }
            }

            foreach ($url->getImages() as $image) {
                if ($this->needHost($image->getLoc())) {
                    $image->setLoc($this->base_host.$image->getLoc());
                }

                if ($this->needHost($image->getLicense())) {
                    $image->setLicense($this->base_host.$image->getLicense());
                }
            }
        }

        $this->dumper->dump($this->formatter->formatUrl($url));

        return $this;
    }

    protected function needHost($url)
    {
        if ($url === null) {
            return false;
        }

        return substr($url, 0, 4) !== 'http';
    }
}
