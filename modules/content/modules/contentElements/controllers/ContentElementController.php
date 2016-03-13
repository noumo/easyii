<?php

namespace yii\easyii\modules\content\modules\contentElements\controllers;

use Yii;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\modules\contentElements\ContentElementBase;
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

	public function actionDelete($elementId)
	{
		/** @var \yii\easyii\modules\content\modules\contentElements\ContentElementBase $element */
		$element = ContentElementBase::findOne(['element_id' => $elementId]);

		if (!$element) {
			throw new NotFoundHttpException('Element not exists');
		}

		if ($element->delete()) {
			return 'success';
		}

		throw new BadRequestHttpException(json_encode($element->firstErrors));
	}
}