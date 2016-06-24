<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\module;

use yii\data\ArrayDataProvider;
use yii\easyii\AdminModule;
use yii\easyii\components\ApiObject;
use yii\easyii\components\IApiHtml;
use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\elements\others\module\models\Element;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Class Widget
 *
 * @property Element $element
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class Widget extends BaseWidget
{
	public $dataTemplatePath = '@app/templates';

	public function actionModuleFunctions($module)
	{
		$activeModule = AdminModule::getInstance()->activeModules[$module];
		if (!$activeModule) {
			return;
		}

		$moduleClass = $activeModule->class;
		$namespace = StringHelper::dirname($moduleClass);

		$api = $namespace . '\\api\\' . ucfirst($module);
		$apiClass = new \ReflectionClass($api);
		$methods = $apiClass->getMethods(\ReflectionMethod::IS_PUBLIC);

		$apiMethods = [];
		foreach ($methods as $method) {
			if (strpos($method->name, 'api_') === 0) {
				$name = str_replace('api_', '', $method->name);
				$apiMethods[$method->name] = [
					'label' => ucfirst($name),
					'value' => $method->name,
				];
			}
		}

		return $apiMethods;
	}

	public function onBeforeRender($view, &$params)
	{
		if ($view === 'view') {
			list($view, $params) = $this->onViewRender($view, $params);
		}
		elseif ($view === 'template') {
			list($view, $params) = $this->onTemplateRender($view, $params);
		}

		return parent::onBeforeRender($view, $params);
	}

	private function onTemplateRender($view, array $params)
	{
		$activeModules = AdminModule::getInstance()->activeModules;
		$params['modules'] = ArrayHelper::getColumn($activeModules, 'title');

		return [$view, $params];
	}

	private function onViewRender($view, array $params)
	{
		$data = $this->element->fetchData();

		if (is_array($data) ) {
			$view = 'listView';
			$dataProvider = new ArrayDataProvider();
			$dataProvider->allModels = $data;
			$data = $dataProvider;
		}
		else {
			$view = 'detailView';
		}

		if (is_string($data)) {
			$view = 'view';
		}
		elseif ($html = $this->generateHtmlData($view, $data)) {
			$data = $html;
			$view = 'view';
		}
		elseif ($data instanceof IApiHtml) {
			$data = $data->toHtml();
			$view = 'view';
		}

		$params['data'] = $data;

		return [$view, $params];
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	private function generateHtmlData($view, ApiObject $data)
	{
		$html = false;

		$dataTemplateFile = $this->getDataTemplateFile($view, $data);
		$dataTemplateFile = \Yii::getAlias($dataTemplateFile);

		if (is_file($dataTemplateFile)) {
			$html = $this->renderFile($dataTemplateFile, ['data' => $data]);
		}

		return $html;
	}

	public function getDataTemplateFile($view, ApiObject $data)
	{
		$moduleName = $data::getModuleName();
		$shortName = $data::getShortName();

		$path = [
			$this->dataTemplatePath,
			$moduleName,
			$shortName,
			$view . '.php'
		];

		return implode(DIRECTORY_SEPARATOR, $path);
	}
}