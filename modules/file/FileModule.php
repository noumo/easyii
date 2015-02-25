<?php
namespace yii\easyii\modules\file;

class FileModule extends \yii\easyii\components\Module
{
    public $settings = [
        'autoSlug' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Files',
            'ru' => 'Файлы',
        ],
        'icon' => 'floppy-disk',
        'order_num' => 30,
    ];
}