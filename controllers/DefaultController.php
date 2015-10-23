<?php
namespace yii\easyii\controllers;

use yii\easyii\models\Module;

class DefaultController extends \yii\easyii\components\Controller
{
    public function actionIndex()
    {
        $notifications = Module::find()->where(['and', ['>', 'notice', 0], ['status' => Module::STATUS_ON]])->sort()->limit(4)->all();

        return $this->render('index', [
            'notifications' => $notifications
        ]);
    }
}