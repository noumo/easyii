<?php
namespace yii\easyii\assets;

class FieldsTableAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/assets/fields_table';
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
