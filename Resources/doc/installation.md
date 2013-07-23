## Installation

### Composer

Add `kphoen/sitemap-bundle` to your required field. Then install/update your
dependencies.

### app/AppKernel.php

Register the `KPhoenSitemapBundle`:

```php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new KPhoen\SitemapBundle\KPhoenSitemapBundle(),
    );
}
```

## Bundle Configuration

### config.yml

The following options are available in the `app/config/config.yml` file:

```yaml
k_phoen_sitemap:
    base_host:         http://www.foo.com
    base_host_sitemap: http://www.foo.com
    limit:             50000
```

**Note:**

> The `base_host` will be prepended to relative urls added to the sitemap.
> The `base_host_sitemap` will be prepended to the sitemap filename (used for sitemap index)
> The `limit` is the number of url allowed in the same sitemap, if defined it will create a sitemap index

### Routing

If you don't want to use the console to generate the sitemap, immport the
routes:

```yaml
kphoen_sitemap:
    resource: "@KPhoenSitemapBundle/Resources/config/routing.yml"
```

This will make the sitemap available from the `/sitemap.xml` URL.


## Next steps

[Return to the index](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Resources/doc/index.md) or [configure your sitemap](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Resources/doc/configuration.md)
