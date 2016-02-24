<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\texteditor\models;

use yii\easyii\modules\content\modules\contentElements\BaseElement;

class Element extends BaseElement
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