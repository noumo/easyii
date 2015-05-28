<?php
namespace yii\easyii\widgets\RedactorMultiLanguage;

use yii\easyii\components\ActiveRecord;
use yii\widgets\InputWidget;

/**
 * Multi language input for Redactor textarea widget.
 * Uses bootstrap 3 tabs, based on webvimark/multilanguage input widget
 * @package app\easyii\widget\RedactorMultiLanguage
 */
class RedactorMultiLanguageInput extends InputWidget
{
    /**
     * Associated model
     * @var ActiveRecord
     */
    public $model;

    /**
     * Name of the model's attribute that the widget should display textareas for
     * @var string
     */
    public $attribute;

    /**
     * @return string
     */
    public function run()
    {
        if ($this->model->hasProperty('mlConfig') AND count($this->model->mlConfig['languages']) > 1) {
            return $this->render('multi');
        } else {
            return $this->render('single');
        }
    }
}