<?php
namespace yii\easyii\modules\gallery;

class GalleryModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'itemsInFolder' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Photo Gallery',
            'ru' => 'Фотогалерея',
            'zh-TW' => '相簿',
        ],
        'icon' => 'camera',
        'order_num' => 90,
    ];
}