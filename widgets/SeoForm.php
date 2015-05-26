<?php
namespace yii\easyii\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

/**
 * Form widget for SEO properties
 * @package yii\easyii\widgets
 */
class SeoForm extends Widget
{
    public $model;

    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            throw new InvalidConfigException('Required `model` param isn\'t set.');
        }
    }

    public function run()
    {
        echo $this->render('seo_form', [
            'model' => $this->model->seoText
        ]);
    }

}