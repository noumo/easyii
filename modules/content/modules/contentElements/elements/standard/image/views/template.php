<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\image\models\Element $element
 */

use yii\helpers\Html;

echo Html::activeLabel($element, 'source', ['class' => 'form-label']);
echo Html::activeFileInput($element, 'source');

echo Html::activeLabel($element, 'altText', ['class' => 'form-label']);
echo Html::activeTextInput($element, 'altText', ['class' => 'form-control']);

echo Html::activeLabel($element, 'title', ['class' => 'form-label']);
echo Html::activeTextInput($element, 'title', ['class' => 'form-control']);
