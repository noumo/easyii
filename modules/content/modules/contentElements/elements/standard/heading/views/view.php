<?php
/**
 * @var \yii\web\View                                                                      $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\heading\models\Element $element
 */

echo \yii\helpers\Html::tag('h' . $element->number, $element->content);