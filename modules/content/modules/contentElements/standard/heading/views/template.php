<?php
/**
 * @var \yii\web\View                                                               $this
 * @var \yii\easyii\modules\content\contentElements\standard\heading\models\Element $element
 */

use yii\helpers\Html;

echo Html::activeLabel($element, 'number', ['class' => 'form-label']);
echo Html::activeTextInput($element, 'number', ['class' => 'form-control header-number']);

echo Html::activeLabel($element, 'content', ['class' => 'form-label']);
echo Html::activeTextInput($element, 'content', ['class' => 'form-control header-content']);
