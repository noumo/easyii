<?php
/**
 * @var \yii\web\View                                                             $this
 * @var Element $element
 * @var \yii\data\BaseDataProvider                                                $data
 */

use \yii\easyii\modules\content\modules\contentElements\elements\others\module\models\Element;

if ($element->format === Element::FORMAT_RAW) {
	$itemView = '_listView';
}
else {
	$itemView = '_' . $element->format . 'View';
}

echo \yii\widgets\ListView::widget([
	'dataProvider' => $data,
	'itemView' => $itemView,
]);