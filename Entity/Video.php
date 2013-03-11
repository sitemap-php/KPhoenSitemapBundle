<?php

namespace KPhoen\SitemapBundle\Entity;


/**
 * Represents a video in a sitemap entry.
 *
 * @see https://developers.google.com/webmasters/videosearch/sitemaps
 */
class Video
{
    const RESTRICTION_DENY  = 'deny';
    const RESTRICTION_ALLOW = 'allow';

    const PLATFORM_TV       = 'tv';
    const PLATFORM_MOBILE   = 'mobile';
    const PLATFORM_WEB      = 'web';

    /*********************
     * Required attributes
     ********************/

    /**
     * A URL pointing to the video thumbnail image file. Images must be at
     * least 160x90 pixels and at most 1920x1080 pixels. We recommend images
     * in .jpg, .png, or. gif formats.
     */
    protected $thumbnail_loc = null;

    /**
     * The title of the video. Maximum 100 characters.
     */
    protected $title = null;

    /**
     * The description of the video. Maximum 2048 characters.
     */
    protected $description = null;

    /**********************
     * Optionnal attributes
     *********************/

    /**
     * You must specify at least one of player_loc or content_loc attributes.
     *
     * A URL pointing to the actual video media file. This file should be in
     * .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv,
     * or other video file format.
     */
    protected $content_loc = null;

    /**
     * You must specify at least one of player_loc or content_loc.
     *
     * A URL pointing to a player for a specific video. Usually this is the
     * information in the src element of an <embed> tag and should not be the
     * same as the content of the <loc> tag.
     *
     * The optional attribute allow_embed specifies whether Google can embed
     * the video in search results. Allowed values are Yes or No.
     *
     * The optional attribute autoplay has a user-defined string (in the
     * example above, ap=1) that Google may append (if appropriate) to the
     * flashvars parameter to enable autoplay of the video.
     * For example: <embed src="http://www.example.com/videoplayer.swf?video=123" autoplay="ap=1"/>.
     *
     * Example player URL for Dailymotion: http://www.dailymotion.com/swf/x1o2g
     */
    protected $player_loc = null;

    /**
     * The duration of the video in seconds. Value must be between 0 and
     * 28800 (8 hours).
     */
    protected $duration = null;

    /**
     * The date after which the video will no longer be available. Don't
     * supply this information if your video does not expire.
     */
    protected $expiration_date = null;

    /**
     * The rating of the video. Allowed values are float numbers in the range
     * 0.0 to 5.0.
     */
    protected $rating = null;

    /**
     * The number of times the video has been viewed.
     */
    protected $view_count = null;

    /**
     * The date the video was first published
     */
    protected $publication_date = null;

    /**
     * No if the video should be available only to users with SafeSearch turned off.
     */
    protected $family_friendly = null;

    /**
     * Tags associated with the video.
     */
    protected $tags = array();

    /**
     * The video's category. For example, cooking. The value should be a
     * string no longer than 256 characters.
     */
    protected $category = null;

    /**
     * A space-delimited list of countries where the video may or may not be
     * played. Allowed values are country codes in ISO 3166 format.
     *
     * @see https://developers.google.com/webmasters/videosearch/countryrestrictions
     */
    protected $restrictions = null;

    /**
     * A link to the gallery (collection of videos) in which this video appears.
     */
    protected $gallery_loc = null;

    /**
     * Indicates whether a subscription (either paid or free) is required to view the video.
     */
    protected $requires_subscription = null;

    /**
     * The video uploader's name.
     */
    protected $uploader = null;

    /**
     * A list of space-delimited platforms where the video may or may not be
     * played. Allowed values are web, mobile, and tv.
     *
     * @see https://developers.google.com/webmasters/videosearch/platformrestrictions
     */
    protected $platforms = null;

    /**
     * Indicates whether the video is a live stream.
     */
    protected $live = null;


    public function setTitle($title)
    {
        if (strlen($title) > 100) {
            throw new \DomainException('The title value must be less than 100 characters');
        }

        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setThumbnailLoc($loc)
    {
        $this->thumbnail_loc = $loc;
        return $this;
    }

    public function getThumbnailLoc()
    {
        return $this->thumbnail_loc;
    }

    public function setDescription($description)
    {
        if (strlen($description) > 2048) {
            throw new \DomainException('The description value must be less than 2,048 characters');
        }

        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setContentLoc($loc)
    {
        $this->content_loc = $loc;
        return $this;
    }

    public function getContentLoc()
    {
        return $this->content_loc;
    }

    public function setPlayerLoc($loc, $allow_embed = true, $autoplay = null)
    {
        if ($loc === null)
        {
            $this->player_loc = null;
            return $this;
        }

        $this->player_loc = array(
            'loc'           => $loc,
            'allow_embed'   => $allow_embed,
            'autoplay'      => $autoplay !== null ? $autoplay : null,
        );
        return $this;
    }

    public function getPlayerLoc()
    {
        return $this->player_loc;
    }

    public function setDuration($duration)
    {
        $duration = (int) $duration;

        if ($duration < 0 || $duration > 28800) {
            throw new \DomainException('The duration must be between 0 and 28800 seconds');
        }

        $this->duration = $duration;
        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setExpirationDate($date)
    {
        if ($date !== null && !$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }

        $this->expiration_date = $date;
        return $this;
    }

    public function getExpirationDate()
    {
        if ($this->expiration_date === null) {
            return null;
        }

        return $this->expiration_date->format(\DateTime::W3C);
    }

    public function setRating($rating)
    {
        $rating = (float) $rating;

        if ($rating < 0 || $rating > 5) {
            throw new \DomainException('The rating must be between 0 and 5');
        }

        $this->rating = $rating;
        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setViewCount($count)
    {
        $count = (int) $count;

        if ($count < 0) {
            throw new \DomainException('The view count must be positive');
        }

        $this->view_count = $count;
        return $this;
    }

    public function getViewCount()
    {
        return $this->view_count;
    }

    public function setPublicationDate($date)
    {
        if ($date !== null && !$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }

        $this->publication_date = $date;
        return $this;
    }

    public function getPublicationDate()
    {
        if ($this->publication_date === null) {
            return null;
        }

        return $this->publication_date->format(\DateTime::W3C);
    }

    public function setFamilyFriendly($friendly)
    {
        $this->family_friendly = (bool) $friendly;
        return $this;
    }

    public function getFamilyFriendly()
    {
        return $this->family_friendly;
    }

    public function setTags($tags)
    {
        if ($tags === null) {
            $this->tags = null;
            return $this;
        }

        if (count($tags) > 32) {
            throw new \DomainException('A maximum of 32 tags is allowed.');
        }

        $this->tags = $tags;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setCategory($category)
    {
        if (strlen($category) > 256) {
            throw new \DomainException('The category value must be less than 256 characters');
        }

        $this->category = $category;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setRestrictions($restrictions, $relationship = self::RESTRICTION_DENY)
    {
        if ($restrictions === null) {
            $this->restrictions = null;
            return $this;
        }

        if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
            throw new \InvalidArgumentException('The relationship must be deny or allow');
        }

        $this->restrictions = array(
            'countries'     => $restrictions,
            'relationship'  => $relationship,
        );

        return $this;
    }

    public function getRestrictions()
    {
        return $this->restrictions;
    }

    public function setGalleryLoc($loc, $title = null)
    {
        if ($loc === null) {
            $this->gallery_loc = null;
            return $this;
        }

        $this->gallery_loc = array(
            'loc'   => $loc,
            'title' => $title
        );

        return $this;
    }

    public function getGalleryLoc()
    {
        return $this->gallery_loc;
    }

    public function setRequiresSubscription($requires_subscription)
    {
        $this->requires_subscription = (bool) $requires_subscription;
        return $this;
    }

    public function getRequiresSubscription()
    {
        return $this->requires_subscription;
    }

    public function setUploader($uploader, $info = null)
    {
        if ($uploader === null) {
            $this->uploader = null;
            return $this;
        }

        $this->uploader = array(
            'name' => $uploader,
            'info' => $info,
        );
        return $this;
    }

    public function getUploader()
    {
        return $this->uploader;
    }

    public function setPlatforms($platforms)
    {
        if ($platforms === null) {
            $this->platforms = null;
            return $this;
        }

        $valid_platforms = array(self::PLATFORM_TV, self::PLATFORM_WEB, self::PLATFORM_MOBILE);
        foreach ($platforms as $platform => $relationship) {
            if (!in_array($platform, $valid_platforms)) {
                throw new \DomainException(sprintf('Invalid platform given. Valid values are: %s', implode(', ', $valid_platforms)));
            }

            if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
                throw new \InvalidArgumentException('The relationship must be deny or allow');
            }
        }

        $this->platforms = $platforms;
        return $this;
    }

    public function getPlatforms()
    {
        return $this->platforms;
    }

    public function setLive($live)
    {
        $this->live = (bool) $live;
        return $this;
    }

    public function getLive()
    {
        return $this->live;
    }
}
