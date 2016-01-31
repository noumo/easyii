<?php
/**
 * @var \yii\web\View                                                             $this
 * @var \yii\easyii\modules\content\contentElements\dynamic\models\DynamicElement $element
 * @var \yii\data\BaseDataProvider $data
 */

echo \yii\widgets\ListView::widget([
	'dataProvider' => $data,
	'itemView' => '_itemView'
]);