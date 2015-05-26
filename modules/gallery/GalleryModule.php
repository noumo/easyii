<?php
namespace yii\easyii\modules\gallery;

use yii\easyii\components\Module;

class GalleryModule extends Module
{
    public $settings = [
        'categoryThumb' => true,
        'itemsInFolder' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Photo Gallery',
            'ru' => 'Фотогалерея',
        ],
        'icon' => 'camera',
        'order_num' => 90,
    ];
}