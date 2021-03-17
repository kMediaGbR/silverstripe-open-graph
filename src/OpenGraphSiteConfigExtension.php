<?php

namespace Kmedia\OpenGraph;

use Intervention\Image\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class OpenGraphSiteConfigExtension extends DataExtension
{
    private static $has_one = [
        'DefaultOpenGraphImage' => Image::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.OpenGraph', [
            UploadField::create('DefaultOpenGraphImage')
        ]);
    }
}
