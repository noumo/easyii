<?php
namespace yii\easyii\modules\faq;

use Yii;

class FaqModule extends \yii\easyii\components\Module
{
    public $settings = [
        'questionHtmlEditor' => true,
        'answerHtmlEditor' => true,
        'enableTags' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}