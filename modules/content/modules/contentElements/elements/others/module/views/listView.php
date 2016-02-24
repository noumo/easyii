<?php
/**
 * @var \yii\web\View                                                             $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\others\module\models\Element $element
 * @var \yii\data\BaseDataProvider                                                $data
 */

echo \yii\widgets\ListView::widget([
	'dataProvider' => $data,
	'itemView' => '_itemView'
]);