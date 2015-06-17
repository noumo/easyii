<?php
namespace yii\easyii\assets;

class DateTimePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower';
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->js[] = 'moment/min/moment-with-locales.js';
            $this->js[] = 'eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js';
            $this->css[] = 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';
        } else {
            $this->js[] = 'moment/min/moment-with-locales.min.js';
            $this->js[] = 'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js';
            $this->css[] = 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';
        }
    }

}