<?php

namespace KPhoen\SitemapBundle\Entity;


/**
 * Represents a sitemap entry.
 *
 * @see http://www.sitemaps.org/protocol.html
 */
class Url
{
    /**
     * Page change frequency constants.
     */
    const CHANGEFREQ_ALWAYS     = 'always';
    const CHANGEFREQ_HOURLY     = 'hourly';
    const CHANGEFREQ_DAILY      = 'daily';
    const CHANGEFREQ_WEEKLY     = 'weekly';
    const CHANGEFREQ_MONTHLY    = 'monthly';
    const CHANGEFREQ_YEARLY     = 'yearly';
    const CHANGEFREQ_NEVER      = 'never';

    /**
     * URL of the page.
     * Should NOT begin with the protocol (as it will be added later) but MUST
     * end with a trailing slash, if your web server requires it. This value
     * must be less than 2,048 characters.
     */
    protected $loc = null;

    /**
     * The date of last modification of the file. This date should be in W3C
     * Datetime format. This format allows you to omit the time portion, if
     * desired, and use YYYY-MM-DD.
     *
     * @Note that this tag is separate from the If-Modified-Since (304) header
     * the server can return, and search engines may use the information from
     * both sources differently.
     *
     * @var \DateTime
     */
    protected $lastmod = null;

    /**
     * How frequently the page is likely to change. This value provides general
     * information to search engines and may not correlate exactly to how often
     * they crawl the page.
     * Valid values are represented as class constants.
     */
    protected $changefreq = null;

    /**
     * The priority of this URL relative to other URLs on your site. Valid
     * values range from 0.0 to 1.0. This value does not affect how your pages
     * are compared to pages on other sites—it only lets the search engines
     * know which pages you deem most important for the crawlers.
     *
     * The default priority of a page is 0.5 (if not set in the sitemap).
     */
    protected $priority = null;


    /**
     * Sets the object location.
     *
     * @note The string is escaped.
     * @see http://www.sitemaps.org/protocol.html#escaping
     *
     * @param string $loc The location. Must be less than 2,048 chars.
     *
     * @return Url For fluent interface
     */
    public function setLoc($loc)
    {
        if (strlen($loc) > 2048) {
            throw new \DomainException('The loc value must be less than 2,048 characters');
        }

        $this->loc = htmlspecialchars($loc, ENT_QUOTES);

        return $this;
    }

    public function getLoc()
    {
        return $this->loc;
    }

    public function setLastmod($lastmod)
    {
        if ($lastmod !== null && !$lastmod instanceof \DateTime) {
            $lastmod = new \DateTime($lastmod);
        }

        $this->lastmod = $lastmod;

        return $this;
    }

    public function getLastmod()
    {
        if ($this->lastmod === null) {
            return null;
        }

        if ($this->getChangefreq() === null || in_array($this->getChangefreq(), array(self::CHANGEFREQ_ALWAYS, self::CHANGEFREQ_HOURLY))) {
            return $this->lastmod->format(\DateTime::W3C);
        }

        return $this->lastmod->format('Y-m-d');
    }

    public function setChangefreq($changefreq)
    {
        $valid_freqs = array(
            self::CHANGEFREQ_ALWAYS, self::CHANGEFREQ_HOURLY, self::CHANGEFREQ_DAILY,
            self::CHANGEFREQ_WEEKLY, self::CHANGEFREQ_MONTHLY, self::CHANGEFREQ_YEARLY,
            self::CHANGEFREQ_NEVER, null
        );

        if (!in_array($changefreq, $valid_freqs)) {
            throw new \DomainException(sprintf('Invalid changefreq given. Valid values are: %s', implode($valid_freqs)));
        }

        $this->changefreq = $changefreq;

        return $this;
    }

    public function getChangefreq()
    {
        return $this->changefreq;
    }

    public function setPriority($priority)
    {
        $priority = (float) $priority;

        if ($priority < 0 || $priority > 1) {
            throw new \DomainException('The priority must be between 0 and 1');
        }

        $this->priority = $priority;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }
}
