<?php
namespace yii\easyii\assets;

class EmptyAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/media';
    public $css = [
        'css/empty.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
