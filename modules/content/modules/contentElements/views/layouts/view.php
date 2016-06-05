<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var array                                                           $config
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */
use yii\easyii\modules\content\modules\contentElements\models\ElementOption;

$tagOption = $element->options[ElementOption::TYPE_TAG];
$tag = $tagOption->value ?: 'div';

echo \yii\helpers\Html::tag($tag, $content, [
	'id' => $element->options[ElementOption::TYPE_ID]->value,
	'class' => $element->options[ElementOption::TYPE_CLASS]->value,
	'style' => $element->options[ElementOption::TYPE_STYLE]->value,
]);