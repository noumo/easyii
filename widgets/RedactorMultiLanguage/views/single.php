<?php use yii\easyii\widgets\Redactor;

echo \yii\helpers\Html::activeLabel($this->context->model, $this->context->attribute);

echo Redactor::widget([
    'model' => $this->context->model,
    'attribute' => $this->context->attribute,
    'options' => $this->context->$options
]);