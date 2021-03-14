<?php

namespace Kmedia\OpenGraph;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class OpenGraphSiteConfigExtension extends DataExtension
{
    private static $has_one = [
        'DefaultOpenGraphImage' => 'Image'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.OpenGraph', [
            UploadField::create('DefaultOpenGraphImage')
        ]);
    }
}
