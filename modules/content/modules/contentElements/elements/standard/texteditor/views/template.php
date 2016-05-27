<?php
/**
 * @var \yii\web\View                                                                         $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\texteditor\models\Element $element
 */

use yii\helpers\Html;
use \yii\helpers\Url;

echo \yii\easyii\widgets\Redactor::widget([
		'model' => $element,
		'attribute' => 'content',
		'settings' => [
			'minHeight' => 200,
			'imageUpload' => Url::to(['/admin/redactor/image-upload', 'dir' => 'content'], true),
			'fileUpload' => Url::to(['/admin/redactor/file-upload', 'dir' => 'content'], true),
			'plugins' => ['fullscreen']
		]
	]
);