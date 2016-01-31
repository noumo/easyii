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
	public static function create(ContentElementBase $element)
	{
		$type = $element->type;

		$class = self::getWidgetClass($type);

		/** @var ContentElementWidget $widget */
		$widget = new $class();
		$widget->element = $element;

		return $widget;
	}

	/**
	 * @param ContentElementbase $model
	 *
	 * @return ContentElementWidget
	 */
	public static function createNew($type)
	{
		$class = self::getWidgetClass($type);

		/** @var ContentElementWidget $widget */
		$widget = new $class();

		return $widget;
	}

	/**
	 * @param $type
	 *
	 * @return string
	 */
	public static function getWidgetClass($type)
	{
		$class = __NAMESPACE__ . "\\$type\\Widget";

		return $class;
	}
}