<?php
namespace yii\easyii\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@easyii/media';
    public $css = [
        'css/admin.css',
    ];
    public $js = [
        'js/admin.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\easyii\assets\SwitcherAsset',
    ];
    public $jsOptions = array(
        'position' => View::POS_HEAD
    );
}
