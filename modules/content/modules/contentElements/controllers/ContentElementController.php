<?php

namespace yii\easyii\modules\content\modules\contentElements\controllers;

use Yii;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class ContentElementController extends Controller
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => \yii\filters\VerbFilter::className(),
				'actions' => [
					'template' => ['post', 'get'],
					'list' => ['get'],
					'delete' => ['delete'],
				],
			],
		];
	}

	public function actionTemplate($type, $parentId)
	{
		if (empty($type)) {
			throw new \InvalidArgumentException('Missing argument "type".');
		}

		$this->layout = false;

		$widget = ContentElementModule::createNewWidget($type);

		$widget->element->parent_element_id = $parentId;
		$widget->element->defaultOptions();
		$widget->element->insert();

		return $widget->runTemplate();
	}

	public function actionList()
	{
		$groupItems = [
			'standard' => [],
			'others' => [],
		];

		$groupDirs = glob(Yii::getAlias('@contentElements/elements/*'), GLOB_ONLYDIR);
		foreach ($groupDirs as $groupDir) {
			$group = basename($groupDir);

			$dirs = glob($groupDir . '/*', GLOB_ONLYDIR);
			foreach ($dirs as $dir) {
				$type = basename($dir);

				$widgetClass = ContentElementModule::getWidgetClass("$group-$type");
				$config = $widgetClass::config();

				$groupItems[$group][$type] = $config;
			}
		}

		return $this->renderPartial('list',
			[
				'groupItems' => $groupItems,
			]);
	}

	public function actionSort()
	{
		$data = Yii::$app->request->post('element');

		$sortOrder = 1;
		foreach ($data as $elementId => $parentId) {
			/** @var BaseElement|false $element */
			$element = BaseElement::findOne(['element_id' => $elementId]);
			$element->order_num = $sortOrder++;
			$element->update(false, ['order_num']);
		}
	}

	public function actionRun($id, $action)
	{
		/** @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element */
		$element = BaseElement::findOne(['element_id' => $id]);
		if (!$element) {
			throw new NotFoundHttpException('Element not exists');
		}

		$widget = ContentElementModule::createWidget($element);

		$params = Yii::$app->request->queryParams;
		unset($params['id'], $params['action']);

		return $widget->runAction($action, $params);
	}

	public function actionDelete($elementId)
	{
		/** @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element */
		$element = BaseElement::findOne(['element_id' => $elementId]);

		if (!$element) {
			throw new NotFoundHttpException('Element not exists');
		}

		if ($element->delete()) {
			return 'success';
		}

		throw new BadRequestHttpException(json_encode($element->firstErrors));
	}
}