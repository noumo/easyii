<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\image\models;

use yii\easyii\modules\content\modules\contentElements\BaseElement;

class Element extends BaseElement
{
	public $source;
	public $altText;
	public $title;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['source', 'altText', 'title'], 'string'],
				[['source', 'altText', 'title'], 'safe']
			]);
	}
}