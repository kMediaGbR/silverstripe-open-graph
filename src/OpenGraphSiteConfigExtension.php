<?php

namespace Kmedia\OpenGraph;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TabSet;
use SilverStripe\ORM\DataExtension;

class OpenGraphSiteConfigExtension extends DataExtension
{
    private static $has_one = [
        'OpenGraphDefaultImage' => Image::class,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        if (!$fields->fieldByName('Root.Metadata')) {
            $fields->addFieldToTab('Root', TabSet::create('Metadata'));
        }

        $fields->addFieldsToTab('Root.Metadata.OpenGraph', [
            HeaderField::create('', 'Open Graph'),
            UploadField::create('OpenGraphDefaultImage', 'Default Image')
        ]);
    }
}
