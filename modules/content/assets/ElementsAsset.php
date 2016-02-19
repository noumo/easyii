<?php
namespace yii\easyii\modules\content\assets;

class ElementsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/content/media';
    public $css = [
        'css/elements.css',
    ];
    public $js = [
        'js/elementListView.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
