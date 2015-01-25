<?php
namespace yii\easyii\modules\feedback\controllers;

use Yii;
use yii\widgets\ActiveForm;

use yii\easyii\modules\feedback\models\Feedback;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Feedback;

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->session->setFlash(Feedback::FLASH_KEY, 'success');
            }
            else{
                Yii::$app->session->setFlash(Feedback::FLASH_KEY, 'error');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}