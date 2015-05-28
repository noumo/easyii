<?php
namespace yii\easyii\modules\page;

use Yii;
use yii\easyii\components\Module;

class PageModule extends Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}