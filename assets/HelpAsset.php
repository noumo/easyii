<?php
namespace yii\easyii\assets;

class HelpAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/media';
    public $css = [
        'css/help.css',
    ];
    public $js = [];
    public $depends = [
        'yii\easyii\assets\AdminAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
