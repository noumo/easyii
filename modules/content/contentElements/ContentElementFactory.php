<?php
namespace yii\easyii\modules\content\contentElements;

use Yii;
use yii\base\Object;

abstract class ContentElementFactory extends Object
{
	/**
	 * @param ContentElementbase $model
	 *
	 * @return ContentElementWidget
	 */
	public static function create(ContentElementBase $model)
	{
		$type = $model->type;

		$class = __NAMESPACE__ . "\\$type\\Widget";

		/** @var ContentElementWidget $widget */
		$widget = new $class($model);

		return $widget;
	}
}