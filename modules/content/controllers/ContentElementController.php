<?php

namespace yii\easyii\modules\content\controllers;

use Yii;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\contentElements\ContentElementBase;

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

	public function actionTemplate($id = null)
	{
		$this->layout = 'templateLayout';

		$id = $id ? : Yii::$app->request->post('id');
		$view = $id . 'Template';

		$element = ContentElementBase::create($id);

		$this->view->title = $id;

		return $this->render($view, ['element' => $element]);
	}
}