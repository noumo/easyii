<?php
/**
 * @var \yii\web\View                                                                     $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\others\markdown\models\Element $element
 */

use \yii\helpers\Html;

echo Html::activeTextarea($element, 'content', ['class' => 'form-control', 'style' => 'min-height: 150px;']);
