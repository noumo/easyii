<?php
namespace yii\easyii\modules\subscribe;

use yii\easyii\components\Module;

class SubscribeModule extends Module
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