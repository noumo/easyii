<?php
namespace yii\easyii\modules\text;

use yii\easyii\components\Module;

class TextModule extends Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Text blocks',
            'ru' => 'Текстовые блоки',
        ],
        'icon' => 'font',
        'order_num' => 20,
    ];
}