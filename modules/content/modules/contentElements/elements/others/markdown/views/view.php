<?php
/**
 * @var \yii\web\View                                                                     $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\others\markdown\models\Element $element
 */

echo \yii\helpers\Markdown::process($element->content);
