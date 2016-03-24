<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\module\models;

use yii\easyii\AdminModule;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\helpers\StringHelper;

class Element extends BaseElement
{
	const FORMAT_RAW = 'raw';
	const FORMAT_LIST = 'list';
	const FORMAT_DETAIL = 'detail';

	public static $formats = [
		self::FORMAT_RAW => 'Raw',
		self::FORMAT_LIST => 'List',
		self::FORMAT_DETAIL => 'Detail'
	];

	public $module;
	public $function;
	public $format;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['module', 'function'], 'string'],
				[['item_id'], 'integer'],
				['format', 'in', 'range' => array_keys(self::$formats)],
				[['module', 'function', 'format', 'item_id'], 'safe']
			]);
	}

	public function fetchData()
	{
		$moduleClass = AdminModule::getInstance()->activeModules[$this->module]->class;
		$namespace = StringHelper::dirname($moduleClass);

		$api = $namespace . '\\api\\' . ucfirst($this->module);
		$function = $this->function;

		$data = call_user_func([$api, $function]);

		return $data;
	}
}
