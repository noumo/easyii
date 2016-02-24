<?php
namespace yii\easyii\modules\content\modules\contentElements\elements\standard\container;

class ContainerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@contentElements/elements/standard/container/media';
    public $css = [
        'elements.css',
    ];
    public $js = [
        'elementListView.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'vova07\imperavi\Asset',
    ];
}
