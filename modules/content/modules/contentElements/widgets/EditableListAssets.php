<?php

namespace yii\easyii\modules\content\modules\contentElements\widgets;

class EditableListAssets extends \yii\web\AssetBundle
{
	public $sourcePath = '@contentElements/widgets/media';

	public $css = [
		'editableList.css',
	];

	public $js = [
		'editableList.js',
	];

	public $depends = ['yii\easyii\assets\NestedSortableAsset'];
}
