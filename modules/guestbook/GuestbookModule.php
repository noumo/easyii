<?php
namespace yii\easyii\modules\guestbook;

class GuestbookModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableTitle' => false,
        'enableEmail' => true,
        'preModerate' => false,
        'enableCaptcha' => true,
        'mailAdminOnNewPost' => true,
        'subjectOnNewPost' => 'Guestbook new post',
        'templateOnNewPost' => '@easyii/modules/guestbook/mail/new_post',
        'subjectNotifyUser' => 'Guestbook post answered',
        'templateNotifyUser' => '@easyii/modules/guestbook/mail/notify_user'
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