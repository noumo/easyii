<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\module\models;

use yii\easyii\AdminModule;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\helpers\StringHelper;

class Element extends BaseElement
{
	const FORMAT_HTML = 'htlm';
	const FORMAT_LIST = 'list';
	const FORMAT_DETAIL = 'detail';

	public static $formats = [
		self::FORMAT_HTML => 'Html',
		self::FORMAT_LIST => 'List',
		self::FORMAT_DETAIL => 'Detail'
	];

	public $module;
	public $function;
	public $parameters = [];
	public $format;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['module', 'function'], 'string'],
				[['item_id'], 'integer'],
				['format', 'in', 'range' => array_keys(self::$formats)],
				[['module', 'function', 'parameters', 'format', 'item_id'], 'safe']
			]);
	}

	public function fetchData()
	{
		$moduleClass = AdminModule::getInstance()->activeModules[$this->module]->class;
		$namespace = StringHelper::dirname($moduleClass);

		$api = $namespace . '\\api\\' . ucfirst($this->module);
		$function = $this->function;
		$parameters = explode(',', $this->parameters);

		$data = call_user_func_array([$api, $function], $parameters);

		return $data;
	}
}
