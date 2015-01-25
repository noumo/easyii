<?php
namespace yii\easyii\modules\catalog;

class CatalogModule extends \yii\easyii\components\Module
{
    public $settings = [
        'itemDescription' => true,

        'categoryThumb' => true,
        'categoryThumbCrop' => true,
        'categoryThumbWidth' => 100,
        'categoryThumbHeight' => 100,

        'itemThumb' => true,
        'itemThumbCrop' => true,
        'itemThumbWidth' => 100,
        'itemThumbHeight' => 100,

        'itemPhotos' => true,
        'photoThumbCrop' => true,
        'photoThumbWidth' => 100,
        'photoThumbHeight' => 100,

        'categoryAutoSlug' => true,
        'itemAutoSlug' => true,
    ];
}