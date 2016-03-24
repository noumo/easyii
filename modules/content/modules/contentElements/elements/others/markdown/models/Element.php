<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\markdown\models;

use yii\easyii\modules\content\modules\contentElements\models\BaseElement;

class Element extends BaseElement
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