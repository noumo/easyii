<?php
namespace yii\easyii\modules\subscribe\controllers;

use Yii;
use yii\easyii\modules\subscribe\api\Subscribe;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

use yii\easyii\modules\subscribe\models\Subscriber;

class SendController extends Controller
{
    public function actionIndex()
    {
        $model = new Subscriber;
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            if($request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                $returnUrl = $model->save() ? $request->post('successUrl') : $request->post('errorUrl');
                return $this->redirect($returnUrl);
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
            throw new BadRequestHttpException(Yii::t('easyii/subscribe/api', 'Incorrect E-mail'));
        }
    }
}