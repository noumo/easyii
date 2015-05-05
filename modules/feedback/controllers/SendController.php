<?php
namespace yii\easyii\modules\feedback\controllers;

use Yii;

use yii\easyii\modules\feedback\api\Feedback;
use yii\easyii\modules\feedback\models\Feedback as FeedbackModel;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new FeedbackModel;

        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $sent = $model->save() ? 1 : 0;
            return $this->redirect(['/' . $request->post('returnUrl'), Feedback::SENT_VAR => $sent]);
        } else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}