<?php
namespace yii\easyii\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\easyii\assets\DateTimePickerAsset;
use yii\easyii\helpers\Data;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\AssetBundle;

class DateTimePicker extends \yii\base\Widget
{
    public $model;
    public $attribute;
    public $options = [];
    public $name;
    public $value;
    public $widgetId;
    public $inputId;

    private $_assetBundle;
    private $_defaultOptions = [
        'showTodayButton' => true,
        'widgetPositioning' => ['vertical' => 'top']
    ];

    public function init()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (empty($this->options['locale'])) {
            $this->options['locale'] = substr(Yii::$app->language, 0, 2);
        }

        $this->options = array_merge($this->_defaultOptions, $this->options);

        $this->inputId = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        $this->widgetId = 'dtp-' . $this->inputId;

        $this->registerAssetBundle();
        $this->registerScript();
    }

    public function run()
    {
        echo '
            <div class="input-group date" id="'.$this->widgetId.'">
                '.Html::textInput('', '', ['class' => 'form-control']).'
                '.($this->hasModel() ? Html::activeHiddenInput($this->model, $this->attribute, ['id' => $this->inputId]) : Html::hiddenInput($this->name, $this->value, ['id' => $this->inputId])).'
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
        ';
    }

    public function registerScript()
    {
        if(empty($this->options['locale'])){
            $this->options['locale'] = Data::getLocale();
        }
        $clientOptions = (count($this->options)) ? Json::encode($this->options) : '';
        $time = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        $this->getView()->registerJs('
            (function(){
                var dtpContainer = $("#'.$this->widgetId.'");

                dtpContainer.datetimepicker('.$clientOptions.')
                .on("dp.change", function (e) {
                    $("#'.$this->inputId.'").val(e.date.unix());
                })
                .data("DateTimePicker")
                .date(moment('.($time * 1000).'));

                $("[type=text]", dtpContainer).focus(function(e){
                    dtpContainer.data("DateTimePicker").show();
                });
            })();
        ');
    }

    public function registerAssetBundle()
    {
        $this->_assetBundle = DateTimePickerAsset::register($this->getView());
    }

    public function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerAssetBundle();
        }
        return $this->_assetBundle;
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }
}
