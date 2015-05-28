<?php use yii\easyii\widgets\Redactor;
use yii\helpers\Url;

echo \yii\helpers\Html::activeLabel($this->context->model, $this->context->attribute);

echo Redactor::widget([
    'model' => $this->context->model,
    'attribute' => $this->context->attribute,
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'Features']),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'Features']),
        'plugins' => ['fullscreen']
    ]
]);