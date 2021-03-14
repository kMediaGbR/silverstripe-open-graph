<?php

namespace Kmedia\OpenGraph;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\ORM\DataExtension;

class OpenGraphExtension extends DataExtension
{
    private static $db = [];

    private static $many_many = [
        'OGImage' => Image::class,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $ogImage = UploadField::create('OGImage', 'Image');
        $ogImage->getValidator()->setAllowedExtensions(['png', 'jpeg', 'jpg', 'gif']);

        $fields->addFieldsToTab('Root.Main', [
            ToggleCompositeField::create([$ogImage])->setHeadingLevel(4)
        ]);
    }
}