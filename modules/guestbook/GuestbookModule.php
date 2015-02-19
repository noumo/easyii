<?php
namespace yii\easyii\modules\guestbook;

class GuestbookModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableTitle' => false,
        'preModerate' => false,
        'enableCaptcha' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Guestbook',
            'ru' => 'Гостевая книга',
        ],
        'icon' => 'book',
        'order_num' => 80,
    ];
}