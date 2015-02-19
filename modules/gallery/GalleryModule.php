<?php
namespace yii\easyii\modules\gallery;

class GalleryModule extends \yii\easyii\components\Module
{
    public $settings = [
        'photoMaxWidth' => 1280,
        'photoThumbWidth' => 100,
        'photoThumbHeight' => 100,
        'photoThumbCrop' => true,

        'albumThumb' => true,
        'albumThumbWidth' => 100,
        'albumThumbHeight' => 100,
        'albumThumbCrop' => true,

        'autoSlug' => true
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