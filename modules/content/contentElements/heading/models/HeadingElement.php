<?php

namespace yii\easyii\modules\content\contentElements\heading\models;

use yii\easyii\modules\content\contentElements\ContentElementBase;

class HeadingElement extends ContentElementBase
{
	public $content;
	public $number;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['content'], 'string'],
				[['number'], 'integer'],
				[['content', 'number'], 'safe']
			]);
	}
}