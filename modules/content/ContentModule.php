<?php
namespace yii\easyii\modules\content;

class ContentModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'item/all';

    public $settings = [
        'layoutThumb' => true,
        'itemsInFolder' => false,

        'itemThumb' => true,
        'itemPhotos' => true,
        'itemDescription' => true,
        'itemSale' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Content',
            'ru' => 'содержание',
        ],
        'icon' => 'list-alt',
        'order_num' => 110,
    ];
}