<?php

namespace KPhoen\SitemapBundle\Formatter;


abstract class BaseFormatter
{
    protected function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }
}
