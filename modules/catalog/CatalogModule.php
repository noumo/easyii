<?php
namespace yii\easyii\modules\catalog;

class CatalogModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'itemsInFolder' => false,

        'itemThumb' => true,
        'itemPhotos' => true,
        'itemDescription' => true,
        'itemSale' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Catalog',
            'ru' => 'Каталог',
            'zh-TW' => '商品目錄',
        ],
        'icon' => 'list-alt',
        'order_num' => 100,
    ];
}