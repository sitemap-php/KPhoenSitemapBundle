## Going further

### Dumpers and formatters

The dumper is the class which takes care of the sitemap's persistance (in
memory, in a file, etc.) and the formatter formats the sitemap.

Currently, the following dumpers are implemented:

  * FileDumper: dumps the sitemap into a file
  * GzFileDumper: dumps the sitemap into a gz compressed file
  * MemoryDumper: dumps the sitemap in memory

And the following formatters are implemented:

  * TextFormatter: formats the sitemap as a simple text file that contains one URL per line
  * XmlFormatter: formats a classic XML sitemap
  * SpacelessFormatter: wraps another formatter and remove the \n and \t characters

The dumpers must implement the DumperInterface and the formatters the
FormatterInterface.

The default sitemap service uses a GzFileDumper and a XmlFormatter. You can
change this by overriding the sitemap service definition:

```yml
services:
    sitemap_text_formatter:
        class:      KPhoen\SitemapBundle\Formatter\TextFormatter

    sitemap:
        class:      KPhoen\SitemapBundle\Sitemap\Sitemap
        arguments:  [ @sitemap_gz_dumper, @sitemap_text_formatter, %sitemap.config.base_host%, %sitemap.config.base_host_sitemap%, %sitemap.config.limit% ]
```

### Images and videos

Images and videos are two objects that are embeddable in sitemaps, and
fortunately, this bundle supports both of them.

Just look the [Image](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Entity/Image.php) and [Video](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Entity/Video.php) class to know how to use them.
