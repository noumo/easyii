<?php
namespace yii\easyii\modules\content\contentElements;

use Yii;
use yii\base\Object;

abstract class ContentElementFactory extends Object
{
	/**
	 * @param string $type
	 *
	 * @return static
	 */
	public static function create($type)
	{
		$namespace = 'yii\easyii\modules\content\contentElements\\';
		$class = $namespace . $type . '\models\Element';

		if (!class_exists($class)) {
			throw new \InvalidArgumentException("The content element type of '$type' not found.");
		}

		$element = new $class;

		return $element;
	}

	/**
	 * @param ContentElementbase $model
	 *
	 * @return ContentElementWidget
	 */
	public static function createWidget(ContentElementBase $element)
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
	public static function createNewWidget($type)
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