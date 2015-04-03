<?php
namespace yii\easyii\modules\article;

class ArticleModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'categoryThumbCrop' => true,
        'categoryThumbWidth' => 100,
        'categoryThumbHeight' => 100,

        'articleThumb' => true,
        'articleThumbCrop' => true,
        'articleThumbWidth' => 100,
        'articleThumbHeight' => 100,

        'enableShort' => true,
        'shortMaxLength' => 255,
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