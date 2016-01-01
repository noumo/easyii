<?php
namespace yii\easyii\modules\carousel;

class CarouselModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableTitle' => true,
        'enableText' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Carousel',
            'ru' => 'Карусель',
            'zh-TW' => '輪播',
        ],
        'icon' => 'picture',
        'order_num' => 40,
    ];
}