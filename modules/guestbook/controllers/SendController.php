<?php
namespace yii\easyii\modules\guestbook\controllers;

use Yii;
use yii\easyii\modules\guestbook\api\Guestbook;
use yii\easyii\modules\guestbook\models\Guestbook as GuestbookModel;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new GuestbookModel;
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $sent = $model->save() ? 1 : 0;
            return $this->redirect(['/' . $request->post('returnUrl'), Guestbook::SENT_VAR => $sent]);
        }
        else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}