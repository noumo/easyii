<?php

namespace yii\easyii\modules\content\modules\contentElements\controllers;

use Yii;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\easyii\modules\content\modules\contentElements\Factory;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\View;

class ContentElementController extends Controller
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => \yii\filters\VerbFilter::className(),
				'actions' => [
					#'template'  => ['post'],
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

		return $this->renderPartial('list', [
			'groupItems' => $groupItems
		]);
	}
}