<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\module\models;

use yii\easyii\modules\content\modules\contentElements\models\BaseElement;

class Element extends BaseElement
{
	const WIDGET_LIST = 'yii\widgets\ListView';
	const WIDGET_DETAIL = 'yii\widgets\DetailView';

	public static $widgets = [
		'listView' => self::WIDGET_LIST,
		'detailView' => self::WIDGET_DETAIL
	];

	public $module;
	public $function;
	public $widgetClass;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['module', 'function'], 'string'],
				[['item_id'], 'integer'],
				['widgetClass', 'in', 'range' => array_keys(self::$widgets)],
				[['module', 'function', 'widgetClass', 'item_id'], 'safe']
			]);
	}

	public function fetchData()
	{
		$module = $this->module;
		$function = $this->function;

		$data = call_user_func([$module, $function]);

		return $data;
	}
}
