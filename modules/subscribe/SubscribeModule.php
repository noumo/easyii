<?php
namespace yii\easyii\modules\subscribe;

class SubscribeModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'E-mail subscribe',
            'ru' => 'E-mail рассылка',
        ],
        'icon' => 'envelope',
        'order_num' => 10,
    ];
}