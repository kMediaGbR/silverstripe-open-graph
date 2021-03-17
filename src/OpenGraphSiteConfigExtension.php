<?php

namespace Kmedia\OpenGraph;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\ORM\DataExtension;

class OpenGraphSiteConfigExtension extends DataExtension
{
    private static $has_many = [
        'OpenGraphDefaultImages' => OpenGraphDefaultImage::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $config = GridFieldConfig_RelationEditor::create();
        $fields->addFieldToTab('Root.OpenGraph',
            GridField::create('OpenGraphsImages', 'OpenGraph Image', $this->owner->OpenGraphDefaultImages(), $config));

        parent::updateCMSFields($fields);
    }

    /*private static $has_one = [
        'DefaultOpenGraphImage' => Image::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.OpenGraph', [
            UploadField::create('DefaultOpenGraphImage')
        ]);
    }*/
}
