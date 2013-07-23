<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Image;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Entity\Video;
use KPhoen\SitemapBundle\Entity\SitemapIndex;


class XmlFormatter extends BaseFormatter implements FormatterInterface
{
    public function getSitemapStart()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset '.
               'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '.
               'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" '.
               'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
    }

    public function getSitemapEnd()
    {
        return '</urlset>';
    }

    public function getSitemapIndexStart()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    }

    public function getSitemapIndexEnd()
    {
        return '</sitemapindex>';
    }

    public function formatUrl(Url $url)
    {
        return '<url>' . "\n" . $this->formatBody($url) . '</url>' . "\n";
    }

    protected function formatBody(Url $url)
    {
        $buffer = "\t" . '<loc>' . $this->escape($url->getLoc()) . '</loc>' . "\n";

        if ($url->getLastmod() !== null) {
            $buffer .= "\t" . '<lastmod>' . $this->escape($url->getLastmod()) .'</lastmod>' . "\n";
        }

        if ($url->getChangefreq() !== null) {
            $buffer .= "\t" . '<changefreq>' . $this->escape($url->getChangefreq()) .'</changefreq>' . "\n";
        }

        if ($url->getPriority() !== null) {
            $buffer .= "\t" . '<priority>' . $this->escape($url->getPriority()) .'</priority>' . "\n";
        }

        foreach ($url->getVideos() as $video) {
            $buffer .= $this->formatVideo($video);
        }

        foreach ($url->getImages() as $image) {
            $buffer .= $this->formatImage($image);
        }

        return $buffer;
    }

    public function formatSitemapIndex(SitemapIndex $sitemapIndex)
    {
        return '<sitemap>' . "\n" . $this->formatSitemapIndexBody($sitemapIndex) . '</sitemap>' . "\n";
    }

    public function formatSitemapIndexBody(SitemapIndex $sitemapIndex)
    {
        $buffer = "\t" . '<loc>' . $this->escape($sitemapIndex->getLoc()) . '</loc>' . "\n";

        if ($sitemapIndex->getLastmod() !== null) {
            $buffer .= "\t" . '<lastmod>' . $this->escape($sitemapIndex->getLastmod()) .'</lastmod>' . "\n";
        }

        return $buffer;
    }

    protected function formatVideo(Video $video)
    {
        $buffer = "\t" . '<video:video>' . "\n";

        $buffer .= "\t\t" . '<video:title>' . $this->escape($video->getTitle()) . '</video:title>' . "\n";
        $buffer .= "\t\t" . '<video:description>' . $this->escape($video->getDescription()) . '</video:description>' . "\n";
        $buffer .= "\t\t" . '<video:thumbnail_loc>' . $this->escape($video->getThumbnailLoc()) . '</video:thumbnail_loc>' . "\n";

        if ($video->getContentLoc() !== null) {
            $buffer .= "\t\t" . '<video:content_loc>' . $this->escape($video->getContentLoc()) . '</video:content_loc>' . "\n";
        }

        if ($video->getPlayerLoc() !== null) {
            $player_loc = $video->getPlayerLoc();
            $allow_embed = $player_loc['allow_embed'] ? 'yes' : 'no';
            $autoplay = $player_loc['autoplay'] !== null ? sprintf(' autoplay="%s"', $this->escape($player_loc['autoplay'])) : '';

            $buffer .= "\t\t" . sprintf('<video:player_loc allow_embed="%s"%s>', $allow_embed, $autoplay) . $this->escape($player_loc['loc']) . '</video:player_loc>' . "\n";
        }

        if ($video->getDuration() !== null) {
            $buffer .= "\t\t" . '<video:duration>' . $this->escape($video->getDuration()) . '</video:duration>' . "\n";
        }

        if ($video->getExpirationDate() !== null) {
            $buffer .= "\t\t" . '<video:expiration_date>' . $this->escape($video->getExpirationDate()) . '</video:expiration_date>' . "\n";
        }

        if ($video->getRating() !== null) {
            $buffer .= "\t\t" . '<video:rating>' . $this->escape($video->getRating()) . '</video:rating>' . "\n";
        }

        if ($video->getViewCount() !== null) {
            $buffer .= "\t\t" . '<video:view_count>' . $this->escape($video->getViewCount()) . '</video:view_count>' . "\n";
        }

        if ($video->getPublicationDate() !== null) {
            $buffer .= "\t\t" . '<video:publication_date>' . $this->escape($video->getPublicationDate()) . '</video:publication_date>' . "\n";
        }

        if ($video->getFamilyFriendly() === false) {
            $buffer .= "\t\t" . '<video:family_friendly>no</video:family_friendly>' . "\n";
        }

        if ($video->getTags() !== null) {
            foreach ($video->getTags() as $tag) {
                $buffer .= "\t\t" . '<video:tag>' . $this->escape($tag) . '</video:tag>' . "\n";
            }
        }

        if ($video->getCategory() !== null) {
            $buffer .= "\t\t" . '<video:category>' . $this->escape($video->getCategory()) . '</video:category>' . "\n";
        }

        if ($video->getRestrictions() !== null) {
            $restrictions = $video->getRestrictions();
            $relationship = $this->escape($restrictions['relationship']);

            $buffer .= "\t\t" . '<video:restriction relationship="' . $relationship . '">'. $this->escape(implode(' ', $restrictions['countries'])) . '</video:restriction>' . "\n";
        }

        if ($video->getGalleryLoc() !== null) {
            $gallery_loc = $video->getGalleryLoc();
            $title = $gallery_loc['title'] !== null ? sprintf(' title="%s"', $this->escape($gallery_loc['title'])) : '';

            $buffer .= "\t\t" . sprintf('<video:gallery_loc%s>', $title) . $this->escape($gallery_loc['loc']) . '</video:gallery_loc>' . "\n";
        }

        if ($video->getRequiresSubscription() !== null) {
            $buffer .= "\t\t" . '<video:requires_subscription>' . ($video->getRequiresSubscription() ? 'yes' : 'no') . '</video:requires_subscription>' . "\n";
        }

        if ($video->getUploader() !== null) {
            $uploader = $video->getUploader();
            $info = $uploader['info'] !== null ? sprintf(' info="%s"', $this->escape($uploader['info'])) : '';

            $buffer .= "\t\t" . sprintf('<video:uploader%s>', $info) . $this->escape($uploader['name']) . '</video:uploader>' . "\n";
        }

        if ($video->getPlatforms() !== null) {
            foreach ($video->getPlatforms() as $platform => $relationship) {
                $buffer .= "\t\t" . '<video:platform relationship="' . $this->escape($relationship) . '">'. $this->escape($platform) . '</video:platform>' . "\n";
            }
        }

        if ($video->getLive() !== null) {
            $buffer .= "\t\t" . '<video:live>' . ($video->getLive() ? 'yes' : 'no') . '</video:live>' . "\n";
        }

        return $buffer . "\t" . '</video:video>' . "\n";
    }

    protected function formatImage(Image $image)
    {
        $buffer = "\t" . '<image:image>' . "\n";

        $buffer .= "\t\t" . '<image:loc>' . $this->escape($image->getLoc()) . '</image:loc>' . "\n";

        if ($image->getCaption() !== null) {
            $buffer .= "\t\t" . '<image:caption>' . $this->escape($image->getCaption()) . '</image:caption>' . "\n";
        }

        if ($image->getGeoLocation() !== null) {
            $buffer .= "\t\t" . '<image:geo_location>' . $this->escape($image->getGeoLocation()) . '</image:geo_location>' . "\n";
        }

        if ($image->getTitle() !== null) {
            $buffer .= "\t\t" . '<image:title>' . $this->escape($image->getTitle()) . '</image:title>' . "\n";
        }

        if ($image->getLicense() !== null) {
            $buffer .= "\t\t" . '<image:license>' . $this->escape($image->getLicense()) . '</image:license>' . "\n";
        }

        return $buffer . "\t" . '</image:image>' . "\n";
    }
}
