<?php
namespace yii\easyii\assets;

use yii\web\AssetBundle;

class EmptyAsset extends AssetBundle
{
    public $sourcePath = '@easyii/media';
    public $css = [
        'css/empty.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
