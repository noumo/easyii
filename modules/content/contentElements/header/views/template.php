<?php
/**
 * @var \yii\web\View                                                           $this
 * @var \yii\easyii\modules\content\contentElements\header\models\HeaderElement $model
 */

use yii\helpers\Html;

echo Html::activeHiddenInput($model, 'type');


echo Html::activeLabel($model, 'number', ['class' => 'form-label']);
echo Html::activeTextInput($model, 'number', ['class' => 'form-control header-number']);

echo Html::activeLabel($model, 'content', ['class' => 'form-label']);
echo Html::activeTextInput($model, 'content', ['class' => 'form-control header-content']);
