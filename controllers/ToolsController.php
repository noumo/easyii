<?php
namespace yii\easyii\controllers;

use Yii;

class ToolsController extends \yii\easyii\components\Controller
{
    public function actionLiveEdit($id)
    {
        Yii::$app->session->set('easyii_live_edit', $id);
        $this->back();
    }
}