<?php

namespace KPhoen\SitemapBundle\Provider;

use Symfony\Component\Routing\RouterInterface;

use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Sitemap\Sitemap;


class PropelProvider implements ProviderInterface
{
    protected $router = null;

    protected $options = array(
        'model'         => null,
        'loc'           => array(),
        'filters'       => array(),
        'lastmod'       => null,
        'priority'      => null,
        'changefreq'    => null,
    );


    public function __construct(RouterInterface $router, $options)
    {
        $this->router = $router;
        $this->options = array_merge($this->options, $options);

        if (!class_exists($options['model'])) {
            throw new \LogicException('Can\'t find class ' . $options['model']);
        }
    }

    public function populate(Sitemap $sitemap)
    {
        $query = $this->getQuery($this->options['model']);

        // apply filters on the query
        foreach ($this->options['filters'] as $filter) {
            $query->$filter();
        }

        // and populate the sitemap!
        foreach ($query->find() as $result) {
            $sitemap->add($this->resultToUrl($result));
        }
    }

    protected function resultToUrl($result)
    {
        $url = new Url();
        $url->setLoc($this->getResultLoc($result));

        if ($this->options['priority'] !== null) {
            $url->setPriority($this->options['priority']);
        }

        if ($this->options['changefreq'] !== null) {
            $url->setChangefreq($this->options['changefreq']);
        }

        if ($this->options['lastmod'] !== null) {
            $url->setLastmod($this->getColumnValue($result, $this->options['lastmod']));
        }

        return $url;
    }

    protected function getResultLoc($result)
    {
        $route = $this->options['loc']['route'];
        $params = array();

        if (!isset($this->options['loc']['params'])) {
            $this->options['loc']['params'] = array();
        }

        foreach ($this->options['loc']['params'] as $key => $column) {
            $params[$key] = $this->getColumnValue($result, $column);
        }

        return $this->router->generate($route, $params);
    }

    protected function getColumnValue($result, $column)
    {
        $method = 'get'.$column;

        if (!method_exists($result, $method)) {
            throw new \RuntimeException(sprintf('"%s" method not found in "%s"', $method, $this->options['model']));
        }

        return $result->$method();
    }

    protected function getQuery($model)
    {
        return \PropelQuery::from($model)->setFormatter('PropelOnDemandFormatter');
    }
}
