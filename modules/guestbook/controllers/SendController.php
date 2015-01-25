<?php
namespace yii\easyii\modules\guestbook\controllers;

use Yii;
use yii\widgets\ActiveForm;

use yii\easyii\modules\guestbook\models\Guestbook;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Guestbook;

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->session->setFlash(Guestbook::FLASH_KEY, 'success');
            }
            else{
                Yii::$app->session->setFlash(Guestbook::FLASH_KEY, 'error');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}