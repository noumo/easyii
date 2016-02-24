<?php

namespace yii\easyii\modules\content\contentElements\standard\html\models;

use yii\easyii\modules\content\contentElements\ContentElementBase;

class Element extends ContentElementBase
{
	public $content;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['content'], 'string'],
				[['content'], 'safe']
			]);
	}
}