<?php
namespace yii\easyii\controllers;

class DefaultController extends \yii\easyii\components\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}