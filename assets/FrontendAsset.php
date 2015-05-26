<?php
namespace yii\easyii\assets;

use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle
{
    public $sourcePath = '@easyii/media';
    public $css = [
        'css/frontend.css',
    ];
    public $js = [
        'js/frontend.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\easyii\assets\SwitcherAsset'
    ];
}
