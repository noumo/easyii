<?php
namespace yii\easyii\assets;

class ColorboxAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@easyii/assets/colorbox';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        list($locale,) = explode('-', \Yii::$app->language);

        if (YII_DEBUG) {
            $this->js[] = 'jquery.colorbox.js';
        } else {
            $this->js[] = 'jquery.colorbox-min.js';
        }
        if($locale !== 'en'){
            $this->js[] = 'i18n/jquery.colorbox-'.$locale.'.js';
        }

        $this->css[] = 'colorbox.css';
    }

}