<?php
namespace yii\easyii\modules\entity;

class EntityModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'itemsInFolder' => false,

        'itemThumb' => true,
        'itemPhotos' => true,

        'categorySlugImmutable' => false,
        'itemSlugImmutable' => false
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Entity',
            'ru' => 'Объекты',
        ],
        'icon' => 'list-asterisk',
        'order_num' => 95,
    ];
}