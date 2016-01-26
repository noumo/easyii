<?php
namespace yii\easyii\modules\content\models;

use yii;
use yii\easyii\components\ActiveRecord;
use yii\easyii\components\ApiObject;

class Element extends ActiveRecord
{
	public static function tableName()
	{
		return 'easyii_content_element';
	}

	public function rules()
	{
		return [
			[['moduleName', 'functionName']],
			[['item_id'], 'integer']
		];
	}

	public function attributeLabels()
	{
		return [];
	}

	public function behaviors()
	{
		return [];
	}

	/**
	 * @return ApiObject|ApiObject[]
	 */
	public function getData()
	{
		/** @var \yii\easyii\components\API $moduleName */
		$moduleName = $this->moduleName;
		$functionName = $this->functionName;

		/** @var ApiObject|ApiObject[] $result */
		$result = $moduleName::$functionName();

		return $result;
	}
}