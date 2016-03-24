<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\image\models\Element $element
 */

echo \yii\helpers\Html::img($element->getImageSource(), ['alt' => $element->altText, 'title' => $element->title]);