<?php
/**
 * @var \yii\web\View                                                           $this
 * @var \yii\easyii\modules\content\contentElements\header\models\HeaderElement $model
 */

use yii\helpers\Html;

echo Html::tag('h' . $model->number, $model->content);