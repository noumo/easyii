<?php
namespace yii\easyii\modules\faq;

use Yii;

class FaqModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
            'zh-TW' => '問與答',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}