<?php
namespace yii\easyii\widgets;

use yii\base\InvalidConfigException;
use yii\easyii\models\Setting;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use yii\helpers\Html;

class GooglePlacesAutoComplete extends InputWidget {
    const API_URL = '//maps.googleapis.com/maps/api/js?';

    public $libraries = 'places';
    public $sensor = true;
    public $key;

    public $language;
    public $autocompleteOptions = [];
    public $inputId;
    public $hiddenId;

    public function init()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if(!$this->key && !($this->key = Setting::get('gm_api_key'))) {
            throw new InvalidConfigException("You cannot use Google places autocomplete. Setting 'gm_api_key' empty or not found.");
        }
        $this->language = $this->language ? $this->language : \Yii::$app->language;

        $this->inputId = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        $this->hiddenId = 'hidden-' . $this->inputId;

        $this->registerClientScript();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $visibleValue = '';
        if($this->value) {
            $parts = explode('#', $this->value);
            if(count($parts) == 4) {
                $visibleValue = $parts[0];
            }
        }

        echo '
            <div class="input-group">
                '.Html::textInput('', $visibleValue, ['class' => 'form-control gmaps-autocomplete', 'id' => $this->inputId]).'
                '.($this->hasModel() ? Html::activeHiddenInput($this->model, $this->attribute, ['id' => $this->hiddenId]) : Html::hiddenInput($this->name, $this->value, ['id' => $this->hiddenId])).'
                <span class="input-group-btn">
                    <button class="btn btn-default show-on-map ' . (empty($parts[1]) ? 'disabled' : '') . '" type="button"><i class="glyphicon glyphicon-map-marker"></i> ' . \Yii::t('easyii', 'Show on map') . '</button>
                </span>
            </div>
        ';
    }
    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript(){
        $scriptOptions = json_encode($this->autocompleteOptions);
        $view = $this->getView();
        $view->registerJsFile(self::API_URL . http_build_query([
                'key' => Setting::get('gm_api_key'),
                'libraries' => $this->libraries,
                'sensor' => $this->sensor ? 'true' : 'false',
                'language' => $this->language
        ]));
        $view->registerJs(new JsExpression('
        (function(){
            var input = $("#' . $this->inputId . '");
            var hiddenInput = $("#' . $this->hiddenId . '");
            var autoComplete = new google.maps.places.Autocomplete(input.get(0), ' . $scriptOptions . ');

            google.maps.event.addListener(autoComplete, "place_changed", function () {
                var place = autoComplete.getPlace();
                var addressString = [place.formatted_address, place.place_id, place.geometry.location.lat(), place.geometry.location.lng()].join("#");
                hiddenInput.val(addressString);
                input.parent().find(".show-on-map").removeClass("disabled");
            });
        })();
        '), \yii\web\View::POS_READY);

        $view->registerJs(new JsExpression(' 
        $(".gmaps-autocomplete").on("change", function(e){
            var $this = $(this);
            
            if($this.val() == "") {
                $this.siblings("input[type=hidden]").val("");
                $this.parent().find(".show-on-map").addClass("disabled");
            }
        }).on("keyup keypress", function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                return false;
            }
        });
        
        $(".show-on-map").click(function(){
            var addressString = $(this).closest(".input-group").find("input[type=hidden]").val();
            if(addressString) {
                var parts = addressString.split("#");
                if(parts.length == 4) {
                    $.fancybox(\'<iframe width="640" height="480" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:\' + parts[1] + \'&key=' . Setting::get('gm_api_key') . '&language=' . $this->language . '" allowfullscreen></iframe>\');
                }
            }
        });
        '), \yii\web\View::POS_READY, 'showonmap');
    }
}