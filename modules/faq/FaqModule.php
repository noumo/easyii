<?php
namespace yii\easyii\modules\faq;

use Yii;
use yii\easyii\components\Module;

class FaqModule extends Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}