<?php
/**
 * @var \yii\web\View                                                             $this
 * @var \yii\easyii\modules\content\contentElements\dynamic\models\DynamicElement $element
 * @var \yii\data\BaseDataProvider $dataProvider
 * @var string $itemView
 */

echo \yii\widgets\ListView::widget([
	'dataProvider' => $dataProvider,
	'itemView' => $itemView
]);