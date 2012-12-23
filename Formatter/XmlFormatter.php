<?php

namespace KPhoen\SitemapBundle\Formatter;

use KPhoen\SitemapBundle\Entity\Image;
use KPhoen\SitemapBundle\Entity\Url;
use KPhoen\SitemapBundle\Entity\Video;


class XmlFormatter implements FormatterInterface
{
    public function getSitemapStart()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '.
              'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" '.
              'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
    }

    public function getSitemapEnd()
    {
        return '</urlset>';
    }

    public function formatUrl(Url $url)
    {
        $buffer = '<url>' . "\n";

        $buffer .= "\t" . '<loc>' . $url->getLoc() . '</loc>' . "\n";

        if ($url->getLastmod() !== null) {
            $buffer .= "\t" . '<lastmod>' . $url->getLastmod() .'</lastmod>' . "\n";
        }

        if ($url->getChangefreq() !== null) {
            $buffer .= "\t" . '<changefreq>' . $url->getChangefreq() .'</changefreq>' . "\n";
        }

        if ($url->getPriority() !== null) {
            $buffer .= "\t" . '<priority>' . $url->getPriority() .'</priority>' . "\n";
        }

        foreach ($url->getVideos() as $video) {
            $buffer .= $this->formatVideo($video);
        }

        foreach ($url->getImages() as $image) {
            $buffer .= $this->formatImage($image);
        }

        return $buffer . '</url>' . "\n";
    }

    protected function formatVideo(Video $video)
    {
        $buffer = "\t" . '<video:video>' . "\n";

        $buffer .= "\t\t" . '<video:title>' . $video->getTitle() . '</video:title>' . "\n";
        $buffer .= "\t\t" . '<video:description>' . $video->getDescription() . '</video:description>' . "\n";
        $buffer .= "\t\t" . '<video:thumbnail_loc>' . $video->getThumbnailLoc() . '</video:thumbnail_loc>' . "\n";

        if ($video->getContentLoc() !== null) {
            $buffer .= "\t\t" . '<video:content_loc>' . $video->getContentLoc() . '</video:content_loc>' . "\n";
        }

        if ($video->getPlayerLoc() !== null) {
            $player_loc = $video->getPlayerLoc();
            $allow_embed = $player_loc['allow_embed'] ? 'yes' : 'no';
            $autoplay = $player_loc['autoplay'] !== null ? sprintf(' autoplay="%s"', $player_loc['autoplay']) : '';

            $buffer .= "\t\t" . sprintf('<video:player_loc allow_embed="%s"%s>', $allow_embed, $autoplay) . $player_loc['loc'] . '</video:player_loc>' . "\n";
        }

        if ($video->getDuration() !== null) {
            $buffer .= "\t\t" . '<video:duration>' . $video->getDuration() . '</video:duration>' . "\n";
        }

        if ($video->getExpirationDate() !== null) {
            $buffer .= "\t\t" . '<video:expiration_date>' . $video->getExpirationDate() . '</video:expiration_date>' . "\n";
        }

        if ($video->getRating() !== null) {
            $buffer .= "\t\t" . '<video:rating>' . $video->getRating() . '</video:rating>' . "\n";
        }

        if ($video->getViewCount() !== null) {
            $buffer .= "\t\t" . '<video:view_count>' . $video->getViewCount() . '</video:view_count>' . "\n";
        }

        if ($video->getPublicationDate() !== null) {
            $buffer .= "\t\t" . '<video:publication_date>' . $video->getPublicationDate() . '</video:publication_date>' . "\n";
        }

        if ($video->getFamilyFriendly() === false) {
            $buffer .= "\t\t" . '<video:family_friendly>no</video:family_friendly>' . "\n";
        }

        if ($video->getTags() !== null) {
            foreach ($video->getTags() as $tag) {
                $buffer .= "\t\t" . '<video:tag>' . $tag . '</video:tag>' . "\n";
            }
        }

        if ($video->getCategory() !== null) {
            $buffer .= "\t\t" . '<video:category>' . $video->getCategory() . '</video:category>' . "\n";
        }

        if ($video->getRestrictions() !== null) {
            $restrictions = $video->getRestrictions();
            $relationship = $restrictions['relationship'];

            $buffer .= "\t\t" . '<video:restriction relationship="' . $relationship . '">'. implode(' ', $restrictions['countries']) . '</video:restriction>' . "\n";
        }

        if ($video->getGalleryLoc() !== null) {
            $gallery_loc = $video->getGalleryLoc();
            $title = $gallery_loc['title'] !== null ? sprintf(' title="%s"', $gallery_loc['title']) : '';

            $buffer .= "\t\t" . sprintf('<video:gallery_loc%s>', $title) . $gallery_loc['loc'] . '</video:gallery_loc>' . "\n";
        }

        if ($video->getRequiresSubscription() !== null) {
            $buffer .= "\t\t" . '<video:requires_subscription>' . ($video->getRequiresSubscription() ? 'yes' : 'no') . '</video:requires_subscription>' . "\n";
        }

        if ($video->getUploader() !== null) {
            $uploader = $video->getUploader();
            $info = $uploader['info'] !== null ? sprintf(' info="%s"', $uploader['info']) : '';

            $buffer .= "\t\t" . sprintf('<video:uploader%s>', $info) . $uploader['name'] . '</video:uploader>' . "\n";
        }

        if ($video->getPlatforms() !== null) {
            foreach ($video->getPlatforms() as $platform => $relationship) {
                $buffer .= "\t\t" . '<video:platform relationship="' . $relationship . '">'. $platform . '</video:platform>' . "\n";
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

        $buffer .= "\t\t" . '<image:loc>' . $image->getLoc() . '</image:loc>' . "\n";

        if ($image->getCaption() !== null) {
            $buffer .= "\t\t" . '<image:caption>' . $image->getCaption() . '</image:caption>' . "\n";
        }

        if ($image->getGeoLocation() !== null) {
            $buffer .= "\t\t" . '<image:geo_location>' . $image->getGeoLocation() . '</image:geo_location>' . "\n";
        }

        if ($image->getTitle() !== null) {
            $buffer .= "\t\t" . '<image:title>' . $image->getTitle() . '</image:title>' . "\n";
        }

        if ($image->getLicense() !== null) {
            $buffer .= "\t\t" . '<image:license>' . $image->getLicense() . '</image:license>' . "\n";
        }

        return $buffer . "\t" . '</image:image>' . "\n";
    }
}
