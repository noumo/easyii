<?php
namespace yii\easyii\modules\guestbook;

class GuestbookModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableTitle' => false,
        'preModerate' => false,
        'enableCaptcha' => true,
    ];
}