<?php
namespace yii\easyii\modules\file;

use yii\easyii\components\Module;

class FileModule extends Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Files',
            'ru' => 'Файлы',
        ],
        'icon' => 'floppy-disk',
        'order_num' => 30,
    ];
}