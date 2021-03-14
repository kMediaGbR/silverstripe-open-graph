<?php

namespace Kmedia\OpenGraph;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;

class OpenGraphExtension extends DataExtension
{
    private static $db = [
        'OGTitle' => 'Varchar(255)',
        'OGDescription' => 'Text',
        'OGType' => 'Varchar(255)',
        'OGExtraMeta' => "HTMLFragment(['whitelist' => ['meta', 'link']])",
    ];

    private static $many_many = [
        'OGImage' => Image::class,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $ogTitle = TextField::create('OGTitle', 'Title');
        $ogTitle->setRightTitle('Page title goes here, automatically defaults to the page title');
        $ogDescription = TextareaField::create('OGDescription', 'Description');
        $ogDescription->setRightTitle('Page description goes here, automatically defaults to the content summary');
        $ogType = TextField::create('OGType', 'Type');
        $ogImage = UploadField::create('OGImage', 'Image');
        $ogImage
            ->setRightTitle('Ideal size: 1200px * 630px')
            ->getValidator()
            ->setAllowedExtensions(['png', 'jpeg', 'jpg', 'gif']);
        $ogExtraMeta = TextareaField::create('OGExtraMeta', 'Custom Meta Tags');
        $ogExtraMeta->setRightTitle('HTML tags for additional meta information. For example <meta name="article:author" content="John Doe">');
        $fields->addFieldsToTab('Root.Main', [
            ToggleCompositeField::create('OpenGraph', 'Open Graph', [$ogTitle, $ogDescription, $ogType, $ogImage, $ogExtraMeta])->setHeadingLevel(4)
        ]);
    }

    public function MetaTags(&$tags)
    {
        $type = trim($this->owner->OGType) ? $this->owner->OGType : 'website';
        $title = trim($this->owner->OGTitle) ? $this->owner->OGTitle : $this->owner->Title;
        $description = trim($this->owner->OGDescription) ? $this->owner->OGDescription : $this->owner->dbObject('Content')->FirstParagraph();
        $siteConfig = SiteConfig::current_site_config();

        $tags .= sprintf('%2$s<meta name="og:type" content="%1$s">', $type, PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:title" content="%1$s">', $title, PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:url" content="%1$s">', $this->owner->AbsoluteLink(), PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:description" content="%1$s">', $description, PHP_EOL);

        if ($this->owner->OGImage()->first() && $this->owner->OGImage()->first()->exists()) {
            foreach ($this->owner->OGImage() as $image) {
                if ($image->exists()) {
                    $tags .= $this->getImageMetaTags($image);
                }
            }
        } else if ($siteConfig->DefaultOpenGraphImage() && $siteConfig->DefaultOpenGraphImage()->exists()) {
            $tags .= $this->getImageMetaTags($siteConfig->DefaultOpenGraphImage());
        }

        if (trim($this->owner->OGExtraMeta)) {
            $tags .= sprintf('%2$s%1$s', implode(PHP_EOL, $this->convertHTMLtoArray(trim($this->owner->OGExtraMeta))), PHP_EOL);
        }
    }

    private function getImageMetaTags(Image $image) {
        $tags = sprintf('%2$s<meta name="og:image" content="%1$s">', $image->AbsoluteURL, PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:image:secure_url" content="%1$s">', $image->AbsoluteURL, PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:image:type" content="%1$s">', $image->MimeType, PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:image:width" content="%1$s">', $image->Width, PHP_EOL);
        $tags .= sprintf('%2$s<meta name="og:image:height" content="%1$s">', $image->Height, PHP_EOL);
        return $tags;
    }

    private function convertHTMLtoArray(string $html) {
        return preg_split(
            '/(<[a-z0-9=\-:." ^\/]+\/>)|(<[^\/]+>[^<\/]+<\/[a-z0-9]+>)|(<[a-z0-9=\-:." ^\/]+>)/',
            $html,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );
    }
}
