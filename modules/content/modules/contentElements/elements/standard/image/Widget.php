<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\image;

use yii\easyii\helpers\Image;
use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\elements\standard\image\models\Element;
use yii\web\UploadedFile;

class Widget extends BaseWidget
{
	public function save()
	{
		$model = $this->element;
		
		if (isset($_FILES) && \Yii::$app->controller->module->settings['itemThumb']) {

			$model->source = UploadedFile::getInstance($model, 'source');
			if ($model->source && $model->validate(['source'])) {
				$model->source = Image::upload($model->source, 'content');
			} else {
				$model->source = $model->oldAttributes['source'];
			}
		}
	}
}