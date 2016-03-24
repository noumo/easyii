<?php
/**
 * @var \yii\easyii\components\ApiObject $model
 */

if ($model->hasProperty('title')) {
	echo $model->title;
}
elseif ($model->hasProperty('slug')) {
	echo $model->slug;
}
else {
	echo json_encode($model);
}
