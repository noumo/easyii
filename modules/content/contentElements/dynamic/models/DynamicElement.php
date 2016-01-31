<?php

namespace yii\easyii\modules\content\contentElements\dynamic\models;

use yii\easyii\modules\content\contentElements\ContentElementBase;

class DynamicElement extends ContentElementBase
{
	const WIDGET_LIST = 'yii\widgets\ListView';
	const WIDGET_DETAIL = 'yii\widgets\DetailView';

	const WIDGETS = [
		'listView' => self::WIDGET_LIST,
		'detailView' => self::WIDGET_DETAIL
	];

	public $module;
	public $function;
	public $widget;

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['module', 'function'], 'string'],
				[['item_id'], 'integer'],
				['widget', 'in', 'range' => self::WIDGETS],
				[['module', 'function', 'widget', 'item_id'], 'safe']
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