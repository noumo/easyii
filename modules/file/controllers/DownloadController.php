<?php
namespace yii\easyii\modules\file\controllers;

use Yii;
use yii\easyii\modules\file\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DownloadController extends Controller
{
    public function actionIndex($id)
    {
        $model = File::findOne($id);
        if($model){
            $model->updateCounters(['downloads' => 1]);
            Yii::$app->response->sendFile(Yii::getAlias('@webroot'). DIRECTORY_SEPARATOR .$model->file);
        }
        else{
            throw new NotFoundHttpException(Yii::t('easyii/file/api', 'File not found'));
        }
    }
}
