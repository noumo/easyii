<?php
namespace yii\easyii\assets;

class FancyboxAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/fancybox/source';

    public $css = [
        'jquery.fancybox.css',
    ];
    public $js = [
        'jquery.fancybox.pack.js'
    ];

    public $depends = ['yii\web\JqueryAsset'];
}