<?php
namespace yii\easyii\widgets;

use Yii;
use yii\easyii\assets\DateTimePickerAsset;
use yii\easyii\helpers\Data;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\AssetBundle;

class DateTimePicker extends InputWidget
{
    public $widgetId;
    public $options = [];

    private $_assetBundle;
    private $_defaultOptions = [
        'showTodayButton' => true
    ];

    public function init()
    {
        $this->options = array_merge($this->_defaultOptions, $this->options);

        $this->widgetId = 'dtp-'.Html::getInputId($this->model, $this->attribute);
        $this->registerAssetBundle();
        $this->registerScript();
    }

    public function run()
    {
        echo '
            <div class="input-group date" id="'.$this->widgetId.'">
                '.Html::textInput('', '', ['class' => 'form-control']).'
                '.Html::activeHiddenInput($this->model, $this->attribute).'
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
        $time = $this->model->{$this->attribute} ? $this->model->{$this->attribute} : time();
        $this->getView()->registerJs('
            var dtpContainer = $("#'.$this->widgetId.'");

            dtpContainer.datetimepicker('.$clientOptions.')
            .on("dp.change", function (e) {
                $("#'.Html::getInputId($this->model, $this->attribute).'").val(e.date.unix());
            })
            .data("DateTimePicker")
            .date(moment('.($time * 1000).'));

            $("[type=text]", dtpContainer).focus(function(e){
                dtpContainer.data("DateTimePicker").show();
            });
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

}
