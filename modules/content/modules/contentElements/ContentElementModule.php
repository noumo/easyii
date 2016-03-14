<?php
namespace yii\easyii\modules\content\modules\contentElements;

use yii\helpers\Inflector;

class ContentElementModule extends \yii\base\Module
{
	/**
	 * @param string $type
	 *
	 * @return static
	 */
	public static function create($type)
	{
		$namespace = 'yii\easyii\modules\content\modules\contentElements\elements\\';
		$class = $namespace . $type . '\models\Element';

		if (!class_exists($class)) {
			throw new \InvalidArgumentException("The content element type of '$type' not found. ($class)");
		}

		$element = new $class;

		return $element;
	}

	/**
	 * @param BaseElement $model
	 *
	 * @return BaseWidget
	 */
	public static function createWidget(BaseElement $element)
	{
		$type = $element->type;

		$class = self::getWidgetClass($type);

		/** @var BaseWidget $widget */
		$widget = new $class(['element' => $element]);

		return $widget;
	}

	/**
	 * @param BaseElement $model
	 *
	 * @return BaseWidget
	 */
	public static function createWidgetByType($type)
	{
		$element = self::create($type);
		$widget = self::createWidget($element);

		return $widget;
	}

	/**
	 * @param $id
	 *
	 * @return BaseWidget
	 */
	public static function createNewWidget($id)
	{
		$class = self::getWidgetClass($id);

		/** @var BaseWidget $widget */
		$widget = new $class();

		return $widget;
	}

	/**
	 * @param $id
	 *
	 * @return string|BaseWidget
	 */
	public static function getWidgetClass($id)
	{
		$namespace = str_replace('-', '\\', $id);

		$class = __NAMESPACE__ . "\\elements\\$namespace\\Widget";

		return $class;
	}

	public static function getElementId($class, $asArray = false)
	{
		$elementsNS = __NAMESPACE__ . '\\elements\\';
		$class = str_replace($elementsNS, '', $class);

		list($group, $id) = explode('\\', $class);

		$id = Inflector::camel2id($id);

		if ($asArray) {
			return [$group, $id];
		}

		return $group . '\\' . $id;
	}

	public function init()
	{
		\Yii::setAlias('contentElements', '@easyii/modules/content/modules/contentElements');

		parent::init();
	}

	public function getSettings()
	{
		return $this->module->settings;
	}
}