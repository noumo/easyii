<?php

namespace yii\easyii\modules\content\controllers;

use Yii;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\contentElements\ContentElementFactory;
use yii\helpers\Html;

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

		return $widget->run('template');
	}

	public function actionList()
	{
		$icon = '<i class="glyphicon glyphicon-plus font-12"></i> ';
		$items = [];

		$types = ['heading', 'dynamic'];
		foreach ($types as $type) {
			$text = $icon . Yii::t('easyii/content', '{name} element', ['name' => $type]);
			$items[] = Html::button($text, ['class' => 'btn btn-default', 'data-content-element' => $type]);
		}

		echo Html::ul($items, [
			'encode' => false,
			'class' => 'list-inline'
		]);
	}
}