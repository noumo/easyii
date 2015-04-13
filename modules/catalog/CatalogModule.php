<?php
namespace yii\easyii\modules\catalog;

class CatalogModule extends \yii\easyii\components\Module
{
    public $settings = [
        'itemDescription' => true,
        'categoryThumb' => true,
        'itemThumb' => true,
        'itemPhotos' => true,
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