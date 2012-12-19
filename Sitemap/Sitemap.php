<?php

namespace KPhoen\SitemapBundle\Sitemap;

use KPhoen\SitemapBundle\Dumper\DumperInterface;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Formatter\FormatterInterface;
use KPhoen\SitemapBundle\Provider\ProviderInterface;


class Sitemap
{
    protected $providers = array();
    protected $dumper = null;
    protected $formatter = null;


    public function __construct(DumperInterface $dumper, FormatterInterface $formatter)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;
    }

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
        return $this;
    }

    public function setDumper(DumperInterface $dumper)
    {
        $this->dumper = $dumper;
        return $this;
    }

    public function build()
    {
        $this->begin();

        foreach ($this->providers as $provider) {
            $provider->populate($this);
        }

        return $this->end();
    }

    protected function begin()
    {
        $this->dumper->dump($this->formatter->getSitemapStart());
        return $this;
    }

    protected function end()
    {
        return $this->dumper->dump($this->formatter->getSitemapEnd());
    }

    public function add(Url $url)
    {
        $loc = $url->getLoc();
        if (empty($loc)) {
            throw new \InvalidArgumentException('The url MUST have a loc attribute');
        }

        $this->dumper->dump($this->formatter->formatUrl($url));

        return $this;
    }
}
