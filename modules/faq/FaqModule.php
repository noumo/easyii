<?php
namespace yii\easyii\modules\faq;

use Yii;

class FaqModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
            'cn' => '常见问题解答',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}