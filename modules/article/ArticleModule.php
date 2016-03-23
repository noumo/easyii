<?php
namespace yii\easyii\modules\article;

class ArticleModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'categorySlugImmutable' => false,
        'categoryDescription' => true,
        
        'articleThumb' => true,
        'enablePhotos' => true,
        'enableTags' => true,
        'enableShort' => true,
        'shortMaxLength' => 255,

        'itemsInFolder' => false,
        'itemSlugImmutable' => false
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Articles',
            'ru' => 'Статьи',
        ],
        'icon' => 'pencil',
        'order_num' => 65,
    ];
}