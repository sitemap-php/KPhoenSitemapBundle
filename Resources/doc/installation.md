## Instalation

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
    base_host: http://www.foo.com
```

### Routing

If you don't want to use the console to generate the sitemap, immport the
routes:

```yaml
kphoen_sitemap:
    resource: "@KPhoenSitemapBundle/Resources/config/routing.yml"
```

This will make the sitemap available from the `/sitemap.xml` URL.
