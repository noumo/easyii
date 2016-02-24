<?php
/**
 * @var \yii\web\View                                                                     $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\others\markdown\models\Element $element
 */

use \yii\helpers\Html;

echo Html::activeTextInput($element, 'content', ['class' => 'form-control']);
