<?php
namespace yii\easyii\assets;

use yii\web\JqueryAsset;

class NestedSortableAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/nestedSortable';

    public $css = [
    ];
    public $js = [
        'jquery.mjs.nestedSortable.js',
        'http://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
    ];

    public $depends = ['yii\web\JqueryAsset'];
}