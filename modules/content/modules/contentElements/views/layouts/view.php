<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var array                                                           $config
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */
use yii\easyii\modules\content\modules\contentElements\models\ElementOption;

echo \yii\helpers\Html::tag('div', $content, [
	'class' => $element->options[ElementOption::TYPE_HTML_CLASS]->value,
	'style' => $element->options[ElementOption::TYPE_HTML_STYLE]->value,
]);