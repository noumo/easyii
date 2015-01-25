<?php
namespace yii\easyii\assets;

class PhotosAsset extends \yii\web\AssetBundle
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
