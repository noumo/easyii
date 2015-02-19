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
        'photoMaxWidth' => 1280,
        'photoThumbCrop' => true,
        'photoThumbWidth' => 100,
        'photoThumbHeight' => 100,

        'categoryAutoSlug' => true,
        'itemAutoSlug' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Catalog',
            'ru' => 'Каталог',
        ],
        'icon' => 'list-alt',
        'order_num' => 100,
    ];
}