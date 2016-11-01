<?php
namespace yii\easyii\modules\page;

use Yii;

class PageModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
            'cn' => '页面',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}