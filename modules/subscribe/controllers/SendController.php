<?php
namespace yii\easyii\modules\subscribe\controllers;

use Yii;
use yii\widgets\ActiveForm;

use yii\easyii\modules\subscribe\models\Subscriber;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Subscriber;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    Yii::$app->session->setFlash(Subscriber::FLASH_KEY, 'success');
                }
                else{
                    Yii::$app->session->setFlash(Subscriber::FLASH_KEY, 'error');
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }

    public function actionUnsubscribe($email)
    {
        if($email && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            Subscriber::deleteAll(['email' => $email]);
            echo '<h1>'.Yii::t('easyii/subscribe/api', 'You have successfully unsubscribed!').'</h1>';
        }
        else{
            throw new \yii\web\BadRequestHttpException(Yii::t('easyii/subscribe/api', 'Incorrect E-mail'));
        }
    }
}