<?php
namespace yii\easyii\controllers;

use yii\data\ActiveDataProvider;

use yii\easyii\components\Controller;
use yii\easyii\models\LoginForm;

class LogsController extends Controller
{
    public $rootActions = 'all';

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => LoginForm::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }
}