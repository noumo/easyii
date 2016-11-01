<?php
namespace yii\easyii\modules\file;

class FileModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Files',
            'ru' => 'Файлы',
            'cn' => '文件',
        ],
        'icon' => 'floppy-disk',
        'order_num' => 30,
    ];
}