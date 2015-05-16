<?php
namespace yii\easyii\modules\shopcart;

class ShopcartModule extends \yii\easyii\components\Module
{
    public $settings = [
        'mailAdminOnNewOrder' => true,
        'subjectOnNewOrder' => 'New order',
        'templateOnNewOrder' => '@easyii/modules/shopcart/mail/en/new_order',
        'subjectNotifyUser' => 'Your order status changed',
        'templateNotifyUser' => '@easyii/modules/shopcart/mail/en/notify_user',
        'frontendShopcartRoute' => '/shopcart/order',
        'enablePhone' => true,
        'enableEmail' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Orders',
            'ru' => 'Заказы',
        ],
        'icon' => 'shopping-cart',
        'order_num' => 120,
    ];
}