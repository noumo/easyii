<?php
namespace yii\easyii\modules\carousel;

class CarouselModule extends \yii\easyii\components\Module
{
    public $settings = [
        'imageWidth' => 1000,
        'imageHeight' => 400,
        'imageCrop' => true,
        'enableTitle' => true,
        'enableText' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Carousel',
            'ru' => 'Карусель',
        ],
        'icon' => 'picture',
        'order_num' => 40,
    ];
}