<?php
namespace yii\easyii\modules\text;

class TextModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Text blocks',
            'ru' => 'Текстовые блоки',
            'zh-TW' => '文字區塊',
        ],
        'icon' => 'font',
        'order_num' => 20,
    ];
}