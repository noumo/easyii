<?php

namespace yii\easyii\modules\content\modules\contentElements;

use yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\easyii\modules\content\modules\contentElements\models\ElementOption;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\View;

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

	public $wrapper;

	public $wrapperPath = '@contentElements/views/wrappers';

	public $readOnly = false;

	private $_element;

	private $_config = null;

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

	public function getConfig()
	{
		if ($this->_config === null) {
			$this->_config = self::config();
		}

		return $this->_config;
	}

	public function setConfig($value)
	{
		$this->_config = $value;
	}

	public function init()
	{
		ContentElementModule::initAliases();

		parent::init();
	}

	public function run($view = 'view', $params = [])
	{
		$this->wrapper = $this->element->wrapper;

		return $this->render($view, $params);
	}

	public function runTemplate()
	{
		$this->wrapper = 'template';

		if (Yii::$app->request->isAjax) {
			$this->view->clear();
			$this->wrapper = 'templateAjax';
		}

		return $this->render('template');
	}

	public function render($view = 'view', $params = [])
	{
		$params['element'] = $this->element;
		$params['widgetId'] = $this->id;

		list($view, $params) = $this->onBeforeRender($view, $params);
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
			'params' => $params,
		];

		$this->trigger(self::EVENT_BEFORE_RENDER, $event);

		return [$event->data['view'], $event->data['params']];
	}

	public function renderContent($content)
	{
		$wrapperPath = $this->findWrapperFile();
		if ($wrapperPath !== false) {
			return $this->renderFile($wrapperPath, ['content' => $content, 'element' => $this->element, 'config' => $this->getConfig()]);
		}
		else {
			return $content;
		}
	}

	public function load($data)
	{
		$this->element->load($data, '');

		$options = (array)$data['options'];
		foreach ($options as $optionData) {
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

	protected function findWrapperFile()
	{
		if (is_string($this->wrapper) && !empty($this->wrapper)) {
			$wrapper = $this->wrapper;
		}
		else {
			$wrapper = '/default';
		}

		if (strncmp($wrapper, '/', 1) === 0) {
			$wrapperFile = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'wrappers' . DIRECTORY_SEPARATOR . substr($wrapper, 1);
		}
		else {
			$wrapperFile = $wrapper;
		}

		$file = Yii::getAlias($wrapperFile);
		if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
			return $file;
		}

		$path = $this->getViewFilePath($file);

		if (!is_file($path)) {
			$file = Yii::getAlias($this->wrapperPath) . DIRECTORY_SEPARATOR;

			if (strncmp($wrapper, '/', 1) === 0) {
				$file .= substr($wrapper, 1);
			}
			else {
				$file .= $wrapper;
			}
		}

		return $this->getViewFilePath($file);
	}

	/**
	 * @param $file
	 *
	 * @return string
	 */
	private function getViewFilePath($file)
	{
		$view = $this->getView();
		$path = $file . '.' . $view->defaultExtension;
		if ($view->defaultExtension !== 'php' && !is_file($path)) {
			$path = $file . '.php';
		}

		return $path;
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

		//$element->defaultOptions();

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