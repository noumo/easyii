<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\heading\models;

use Yii;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;

class Element extends BaseElement
{
	public $content;
	public $number;

	public static function getHeadings()
	{
		return [
			1 => Yii::t('easyii/content', 'Heading 1'),
			2 => Yii::t('easyii/content', 'Heading 2'),
			3 => Yii::t('easyii/content', 'Heading 3'),
			4 => Yii::t('easyii/content', 'Heading 4'),
			5 => Yii::t('easyii/content', 'Heading 5'),
			6 => Yii::t('easyii/content', 'Heading 6'),
		];
	}

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