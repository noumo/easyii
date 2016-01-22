<?php
namespace yii\easyii\modules\content\assets;

class FieldsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/content/media';
    public $css = [
        'css/fields.css',
    ];
    public $js = [
        'js/fields.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
