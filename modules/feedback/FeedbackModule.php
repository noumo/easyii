<?php
namespace yii\easyii\modules\feedback;

class FeedbackModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableTitle' => true,
        'enablePhone' => true,
        'answerHello' => 'Hello,',
        'answerFooter' => 'Best regards.',
        'enableCaptcha' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Feedback',
            'ru' => 'Обратная связь',
        ],
        'icon' => 'earphone',
        'order_num' => 60,
    ];
}