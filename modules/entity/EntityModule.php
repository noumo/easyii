<?php
namespace yii\easyii\modules\entity;

class EntityModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'categorySlugImmutable' => false,
        'categoryDescription' => true,
        'itemsInFolder' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Entities',
            'ru' => 'Объекты',
        ],
        'icon' => 'list-asterisk',
        'order_num' => 95,
    ];
}