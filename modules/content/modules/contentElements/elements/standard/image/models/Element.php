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
				[['altText', 'title'], 'string'],
				[['source'], 'image'],
				[['source', 'altText', 'title'], 'safe']
			]);
	}

	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			if($this->source){
				@unlink(\Yii::getAlias('@webroot').$this->source);
			}

			return true;
		}

		return false;
	}

}