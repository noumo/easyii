<?php
namespace yii\easyii\assets;

use yii\web\AssetBundle;

class PhotosAsset extends AssetBundle
{
    public $sourcePath = '@easyii/assets/photos';
    public $css = [
        'photos.css',
    ];
    public $js = [
        'photos.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
