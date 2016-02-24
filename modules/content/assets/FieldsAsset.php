<?php
namespace yii\easyii\modules\content\assets;

class FieldsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/content/media';
    public $css = [
        'fields.css',
    ];
    public $js = [
        'fields.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
