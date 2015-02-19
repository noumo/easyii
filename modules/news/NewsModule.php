<?php
namespace yii\easyii\modules\news;

class NewsModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableImage' => true,
        'imageWidth' => 100,
        'imageHeight' => '',
        'imageCrop' => false,

        'enableShort' => true,
        'shortMaxLength' => 256
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'News',
            'ru' => 'Новости',
        ],
        'icon' => 'bullhorn',
        'order_num' => 70,
    ];
}