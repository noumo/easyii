<?php
namespace yii\easyii\modules\catalog\assets;

use yii\web\AssetBundle;

class FieldsAsset extends AssetBundle
{
    public $sourcePath = '@easyii/modules/catalog/media';
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
