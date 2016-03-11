<?php

namespace yii\easyii\modules\content\modules\contentElements;

use yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Inflector;

/**
 * Class ContentElementWidget
 *
 * @property BaseElement $element
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
abstract class BaseWidget extends Widget
{
	public $layout = false;

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

	public function run($view = 'view')
	{
		return $this->render($view);
	}

	public function runTemplate()
	{
		$this->layout = 'contentElement';

		if (Yii::$app->request->isAjax) {
			$this->view->clear();
			$this->layout = 'contentElementAjax';
		}

		return $this->render('template');
	}

	public function render($view = 'view', $params = [])
	{
		$params['element'] = $this->element;
		$params['widgetId'] = $this->id;

		$content = parent::render($view, $params);

		$jsReady = $this->view->js[View::POS_READY];
		unset($this->view->js[View::POS_READY]);
		$this->view->js[View::POS_END] = array_merge($this->view->js[View::POS_END] ? : [], $jsReady ? : []);

		return $this->renderContent($content);
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

	public function load($attributes)
	{
		$this->element->setAttributes($attributes, false);

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

		return new $elementClass();
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