<?php
namespace yii\easyii\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;

class FieldsTable extends Widget
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
        echo $this->render('fields_table', [
            'model' => $this->model
        ]);
    }
}