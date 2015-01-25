<?php
namespace yii\easyii\assets;

class SwitcherAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/assets/switcher';

    public function init()
    {
        if (YII_DEBUG) {
            $this->js[] = 'switcher.js';
            $this->css[] = 'switcher.css';
        } else {
            $this->js[] = 'switcher.min.js';
            $this->css[] = 'switcher.css';
        }
    }
}