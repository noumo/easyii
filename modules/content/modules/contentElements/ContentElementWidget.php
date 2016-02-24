<?php

namespace yii\easyii\modules\content\contentElements;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii;
use yii\web\View;

/**
 * Class ContentElementWidget
 *
 * @property ContentElementBase $element
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
abstract class ContentElementWidget extends Widget
{
	public $layout = false;

	private $_element;

	/**
	 * @return ContentElementBase
	 */
	abstract function createElement();

	public function run($view = 'view')
	{
		return $this->render($view);
	}

	public function render($view = 'view', $params = [])
	{
		$params['element'] = $this->element;

		$content = parent::render($view, $params);

		$jsReady = $this->view->js[View::POS_READY];
		unset($this->view->js[View::POS_READY]);
		$this->view->js[View::POS_END] = array_merge($this->view->js[View::POS_END] ? : [], $jsReady ? : []);
		var_dump($this->view->jsFiles);
		return $this->renderContent($content);
	}

	public function renderContent($content)
	{
		if ($this->layout !== false) {
			return $this->renderFile($this->getLayoutFile(), ['content' => $content, 'element' => $this->element]);
		}
		else {
			return $content;
		}
	}

	public function getLayoutFile()
	{
		$layoutFile = \Yii::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->layout . '.php';

		return $layoutFile;
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
	 * @return ContentElementBase
	 */
	public function getElement()
	{
		if ($this->_element == null) {
			$this->_element = $this->createElement();
		}

		return $this->_element;
	}

	/**
	 * @param ContentElementBase $element
	 */
	public function setElement($element)
	{
		$this->_element = $element;
	}
}