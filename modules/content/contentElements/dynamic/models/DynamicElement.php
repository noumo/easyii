<?php

namespace yii\easyii\modules\content\contentElements\dynamic\models;

use yii\easyii\modules\content\contentElements\ContentElementBase;

class DynamicElement extends ContentElementBase
{
	public $moduleName;
	public $functionName;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['moduleName', 'functionName']],
				[['item_id'], 'integer'],
				[['moduleName', 'item_id'], 'safe']
			]);
	}
}