<?php
namespace yii\easyii\assets;

class NestedSortableAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/nestedSortable';

    public $css = [
    ];
    public $js = [
        'jquery.mjs.nestedSortable.js',
    ];

    public $depends = ['yii\easyii\assets\JqueryUiAsset'];
}