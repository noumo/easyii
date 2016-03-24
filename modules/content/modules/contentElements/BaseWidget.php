<?php

namespace yii\easyii\modules\content\modules\contentElements;

use yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Inflector;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\easyii\modules\content\modules\contentElements\models\ElementOption;

/**
 * Class ContentElementWidget
 *
 * @property BaseElement $element
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
abstract class BaseWidget extends Widget
{
	use WidgetActionTrait;

	const EVENT_BEFORE_RENDER = 'beforeRender';

	public $layout = 'view';

	public $layoutPath = '@contentElements/views/layouts';

	private $_element;

	public static function config()
	{
		list($group, $id) = static::elementId();
		$name = Inflector::id2camel($id);

		return [
			'id' => $group . '\\' . $id,
			'title' => Inflector::titleize($name),
		];
	}

	protected static function elementId()
	{
		return ContentElementModule::getElementId(static::className(), true);
	}

	public function init()
	{
		ContentElementModule::initAliases();

		parent::init();
	}

	public function run($view = 'view', $params = [])
	{
		return $this->render($view, $params);
	}

	public function runTemplate()
	{
		$this->layout = 'template';

		if (Yii::$app->request->isAjax) {
			$this->view->clear();
			$this->layout = 'templateAjax';
		}

		return $this->render('template');
	}

	public function render($view = 'view', $params = [])
	{
		$params['element'] = $this->element;
		$params['widgetId'] = $this->id;

		$this->onBeforeRender($view, $params);
		$content = parent::render($view, $params);

		$jsReady = $this->view->js[View::POS_READY];
		unset($this->view->js[View::POS_READY]);
		$this->view->js[View::POS_END] = array_merge($this->view->js[View::POS_END] ? : [], $jsReady ? : []);

		return $this->renderContent($content);
	}

	public function onBeforeRender($view, &$params)
	{
		$event = new yii\base\Event();
		$event->data = [
			'view' => $view,
			'params' => &$params,
		];

		$this->trigger(self::EVENT_BEFORE_RENDER, $event);
	}

	public function renderContent($content)
	{
		if ($this->layout !== false) {
			return $this->renderFile($this->getLayoutFile(), ['content' => $content, 'element' => $this->element, 'config' => static::config()]);
		}
		else {
			return $content;
		}
	}

	public function load($data)
	{
		$this->element->load($data, '');

		foreach ($data['options'] as $optionData) {
			$optionData['element_id'] = $this->element->primaryKey;

			if ($optionData['scenario'] == 'update') {
				$option = ElementOption::findOne(['option_id' => $optionData['option_id']]);
			}
			else {
				$option = new ElementOption();
			}

			$option->load($optionData, '');
			$option->save();
		}

		return $this->element->validate();
	}

	public function save()
	{
		if (method_exists($this, 'beforeSave')) {
			$this->element->on(yii\db\ActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'beforeSave']);
			$this->element->on(yii\db\ActiveRecord::EVENT_BEFORE_INSERT, [$this, 'beforeSave']);
		}

		return $this->element->save();
	}

	public function getLayoutFile()
	{
		return $this->layoutPath . DIRECTORY_SEPARATOR . $this->layout . '.php';
	}

	public function getEditLink()
	{
		return Url::to(['/admin/content/element/edit/', 'id' => $this->id]);
	}

	public function getCreateLink()
	{
		return Html::a(\Yii::t('easyii/content/api', 'Create page'),
			['/admin/content/element/new'],
			['target' => '_blank']);
	}

	/**
	 * @return BaseElement
	 */
	protected function createElement()
	{
		$widgetClass = static::className();

		$elementClass = str_replace('\Widget', '\models\Element', $widgetClass);

		/** @var BaseElement $element */
		$element = new $elementClass();

		$element->setDefaultOptions();

		return $element;
	}

	/**
	 * @return BaseElement
	 */
	public function getElement()
	{
		if ($this->_element == null) {
			$this->_element = $this->createElement();
		}

		return $this->_element;
	}

	/**
	 * @param BaseElement $element
	 */
	public function setElement($element)
	{
		$this->_element = $element;
	}
}