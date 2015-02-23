<?php
namespace yii\easyii\modules\article;

class ArticleModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'categoryThumbCrop' => true,
        'categoryThumbWidth' => 100,
        'categoryThumbHeight' => 100,

        'itemThumb' => true,
        'itemThumbCrop' => true,
        'itemThumbWidth' => 100,
        'itemThumbHeight' => 100,

        'enableShort' => true,
        'shortMaxLength' => 255,

        'categoryAutoSlug' => true,
        'itemAutoSlug' => true,
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