<?php
namespace yii\easyii\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

use yii\easyii\models\Setting;

class ReCaptcha extends InputWidget
{
    const JS_API_URL = 'https://www.google.com/recaptcha/api.js';

    const THEME_LIGHT = 'light';
    const THEME_DARK = 'dark';

    const TYPE_IMAGE = 'image';
    const TYPE_AUDIO = 'audio';

    /** @var string The color theme of the widget. [[THEME_LIGHT]] (default) or [[THEME_DARK]] */
    public $theme;

    /** @var string The type of CAPTCHA to serve. [[TYPE_IMAGE]] (default) or [[TYPE_AUDIO]] */
    public $type;

    /** @var string Your JS callback function that's executed when the user submits a successful CAPTCHA response. */
    public $jsCallback;

    /** @var array Additional html widget options, such as `class`. */
    public $widgetOptions = [];

    public function init()
    {
        parent::init();
        if (!Setting::get('recaptcha_key')) {
            throw new InvalidConfigException('Required `recaptcha_key` setting isn\'t set.');
        }

        $view = $this->view;
        $view->registerJsFile(
            self::JS_API_URL . '??hl=' . $this->getLanguageSuffix(),
            ['position' => $view::POS_HEAD]
        );
    }

    public function run()
    {
        $this->customFieldPrepare();

        $divOptions = [
            'class' => 'g-recaptcha',
            'data-sitekey' => Setting::get('recaptcha_key')
        ];
        if (empty($this->jsCallback)) {
            $divOptions['data-callback'] = $this->jsCallback;
        }
        if (empty($this->theme)) {
            $divOptions['data-theme'] = $this->theme;
        }
        if (empty($this->type)) {
            $divOptions['data-type'] = $this->type;
        }

        if (isset($this->widgetOptions['class'])) {
            $divOptions['class'] = "{$divOptions['class']} {$this->widgetOptions['class']}";
        }
        $divOptions = $divOptions + $this->widgetOptions;

        echo Html::tag('div', '', $divOptions);
    }

    /**
     * @return string
     */
    protected function getLanguageSuffix()
    {
        $currentAppLanguage = Yii::$app->language;
        $langsExceptions = ['zh_CN', 'zh_TW', 'zh_TW'];

        if (strpos($currentAppLanguage, '_') === false) {
            return $currentAppLanguage;
        }

        if (in_array($currentAppLanguage, $langsExceptions)) {
            return str_replace('_', '-', $currentAppLanguage);
        } else {
            return substr($currentAppLanguage, 0, strpos($currentAppLanguage, '_'));
        }
    }

    protected function customFieldPrepare()
    {
        $view = $this->view;
        if ($this->hasModel()) {
            $inputName = Html::getInputName($this->model, $this->attribute);
            $inputId = Html::getInputId($this->model, $this->attribute);
        } else {
            $inputName = $this->name;
            $inputId = 'recaptcha-' . $this->name;
        }

        if (empty($this->jsCallback)) {
            $jsCode = "var recaptchaCallback = function(response){jQuery('#{$inputId}').val(response);};";
        } else {
            $jsCode = "var recaptchaCallback = function(response){jQuery('#{$inputId}').val(response); {$this->jsCallback}(response);};";
        }
        $this->jsCallback = 'recaptchaCallback';

        $view->registerJs($jsCode, $view::POS_BEGIN);
        echo Html::input('hidden', $inputName, null, ['id' => $inputId]);
    }
}