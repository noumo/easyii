<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\image\models;

use yii\easyii\helpers\Upload;
use yii\easyii\modules\content\modules\contentElements\BaseElement;
use yii\helpers\Json;

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
				[['altText', 'title'], 'safe']
			]);
	}

	public function getImageSource()
	{
		return Upload::getPathUrl($this->source);
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

	protected function parseData()
	{
		$attributes = Json::decode($this->data);
		\Yii::configure($this, $attributes);
	}
}