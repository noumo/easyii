<?php
/**
 * @var \yii\web\View                                                           $this
 * @var \yii\easyii\modules\content\contentElements\header\models\HeaderElement $element
 */

use yii\helpers\Html;

echo Html::tag('h' . $element->number, $element->content);