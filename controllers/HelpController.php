<?php
namespace yii\easyii\controllers;

use Yii;
use yii\easyii\components\Module;
use yii\easyii\models;

class HelpController extends \yii\web\Controller
{
    public function actionView($moduleName)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule($moduleName);
        $readmeContent = file_get_contents($module->readmePath);

        return $this->render('index', [
            'module' => $module,
            'readmeContent' => $readmeContent,
        ]);
    }
}