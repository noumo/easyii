<?php
namespace yii\easyii\assets;

use yii\web\AssetBundle;

class RedactorAsset extends AssetBundle
{

    public $sourcePath = '@easyii/assets/redactor';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        if (YII_DEBUG) {
            $this->js[] = 'redactor.js';
            $this->css[] = 'redactor.css';
        } else {
            $this->js[] = 'redactor.min.js';
            $this->css[] = 'redactor.min.css';
        }
    }

}