<?php
/**
 * @var \yii\web\View                                                           $this
 * @var \yii\easyii\modules\content\contentElements\html\models\HtmlElement $element
 */

\yii\bootstrap\BootstrapAsset::register($this);

use yii\helpers\Html;
use \yii\helpers\Url;

echo Html::activeLabel($element, 'content', ['class' => 'form-label']);
echo Html::activeHiddenInput($element, 'content', ['class' => 'form-control header-content']);

echo \yii\easyii\widgets\Redactor::widget([
		'model' => $element,
		'attribute' => 'content',
		'options' => [
			'minHeight' => 400,
			'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'content'], true),
			'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'content'], true),
			'plugins' => ['fullscreen']
		]
	]
);