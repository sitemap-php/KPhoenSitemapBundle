<?php

namespace KPhoen\SitemapBundle\Entity;


class BaseEntity
{
    protected function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }
}
