## Sitemap configuration

At this point, you should be able to generate sitemaps : `app/console sitemap:generate`.
But as we did not tell the bundle how to add entries in that sitemap, it's
empty.


### Add entries to the sitemap

#### Providers

In order to support any kind of datasource, the sitemap uses providers to fetch
the data.

Exemple provider:

```php
<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Provider\ProviderInterface;
use SitemapGenerator\Sitemap\Sitemap;


class DummyProvider implements ProviderInterface
{
    public function populate(Sitemap $sitemap)
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);
        $url->setLastmod('2012-12-19 02:28');
        $sitemap->add($url);
    }
}
```

All the providers implement the `ProviderInterface`, which define the
`populate()` method.

**Note**: so they can be automatically used by the sitemap, providers have to be
described in the DIC with the `sitemap.provider` tag:

```yml
services:
    sitemap_dummy_provider:
        class: SitemapGenerator\Provider\DummyProvider
        tags:
            -  { name: sitemap.provider }
```

All the services tagged as `sitemap.provider` will be used to generate the
sitemap.


#### Propel provider

A propel provider is included in the bundle. It allows to populate a sitemap
with the content of a table.

Here is how you would configure the provider:

```yml
parameters:
  propel_providers_options:
    model:      ACME\DemoBundle\Model\News
    # /news/{id}
    loc:        {route: news_show, params: {id: slug}}
    # the following parameters are optionnal
    filters:    ['filterByIsValid']
    lastmod:    date
    changefreq: daily
    priority:   0.2

services:
    sitemap_propel_provider:
        class:      SitemapGenerator\Provider\PropelProvider
        arguments:  [ @router, %propel_providers_options% ]
        tags:
            -  { name: sitemap.provider }
```


#### Doctrine provider

A doctrine provider is included in the bundle. It allows to populate a sitemap
with the content of a table.

Here is how you would configure the provider:

```yml
parameters:
  doctrine_providers_options:
    entity:         AcmeDemoBundle:News
    # /news/{id}
    loc:            {route: news_show, params: {id: slug}}
    query_method:   findValidQuery
    # the following parameters are optionnal
    lastmod:        updatedAt
    changefreq:     daily
    priority:       0.2

services:
    sitemap_doctrine_provider:
        class:      SitemapGenerator\Provider\DoctrineProvider
        arguments:  [ @doctrine.orm.entity_manager, @router, %doctrine_providers_options% ]
        tags:
            -  { name: sitemap.provider }
```


## Next steps

[Return to the index](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Resources/doc/index.md)
or [go further](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Resources/doc/more.md)
