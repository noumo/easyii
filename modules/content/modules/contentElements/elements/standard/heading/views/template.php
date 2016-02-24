<?php
/**
 * @var \yii\web\View                                                                      $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\heading\models\Element $element
 */

use yii\helpers\Html;

echo Html::activeLabel($element, 'number', ['class' => 'form-label']);
echo Html::activeDropDownList($element, 'number', $element::getHeadings(), ['class' => 'form-control header-number']);

echo Html::activeLabel($element, 'content', ['class' => 'form-label']);
echo Html::activeTextInput($element, 'content', ['class' => 'form-control header-content']);
