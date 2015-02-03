<?php
namespace yii\easyii\modules\news;

class NewsModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableImage' => true,
        'imageWidth' => 100,
        'imageHeight' => '',
        'imageCrop' => false,

        'enableShort' => true,
        'shortMaxLength' => 256
    ];
}