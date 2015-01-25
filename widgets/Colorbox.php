<?php
namespace yii\easyii\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

use yii\easyii\assets\ColorboxAsset;

class Colorbox extends Widget
{
    public $options = [];
    public $selector;

    public function init()
    {
        parent::init();

        if (empty($this->selector)) {
            throw new InvalidConfigException('Required `reCaptcha` param isn\'t set.');
        }
    }

    public function run()
    {
        $view = Yii::$app->getView();
        $view->registerAssetBundle(ColorboxAsset::className());

        $clientOptions = (count($this->options)) ? Json::encode($this->options) : '';

        $view->registerJs('$("'.$this->selector.'").colorbox('.$clientOptions.');');
    }

}