<?php
namespace yii\easyii\controllers;

use yii\easyii\components\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}