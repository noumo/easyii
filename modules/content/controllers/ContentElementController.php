<?php

namespace yii\easyii\modules\content\controllers;

use Yii;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\contentElements\ContentElementFactory;
use yii\helpers\Html;
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

	public function actionTemplate($type = null)
	{
		$type = $type ?: Yii::$app->request->post('type');

		if (empty($type)) {
			throw new \InvalidArgumentException('Missing argument "type".');
		}

		$widget = ContentElementFactory::createNew($type);
		$widget->layout = 'contentElement';

		$this->layout = false;
		$content = $widget->run('template');

		return $content;
	}

	public function actionList()
	{
		$icon = '<i class="glyphicon glyphicon-plus font-12"></i> ';
		$items = [];

		$dirs = glob(Yii::getAlias('@easyii/modules/content/contentElements/*'), GLOB_ONLYDIR);
		foreach ($dirs as $dir) {
			$type = basename($dir);
			$text = $icon . Yii::t('easyii/content', '{name} element', ['name' => $type]);
			$items[] = Html::button($text, ['class' => 'btn btn-default', 'data-content-element' => $type]);
		}

		echo Html::ul($items, [
			'encode' => false,
			'class' => 'list-inline'
		]);
	}
}