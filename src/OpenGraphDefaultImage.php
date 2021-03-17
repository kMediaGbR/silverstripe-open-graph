<?php

namespace Kmedia\OpenGraph;

use Intervention\Image\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;

class OpenGraphDefaultImage extends DataObject
{
    private static $has_one = [
        'OpenGraphDefaultImage' => Image::class,
        'Config' => SiteConfig::class,
    ];

    private static $owns = [
        'OpenGraphDefaultImage'
    ];
}
