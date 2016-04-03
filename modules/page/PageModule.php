<?php
namespace yii\easyii\modules\page;

use Yii;

class PageModule extends \yii\easyii\components\Module
{
    public $settings = [
        'slugImmutable' => false
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}