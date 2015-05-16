<?php
namespace yii\easyii\modules\shopcart\controllers;

use Yii;
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\easyii\modules\shopcart\models\Order;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Order();
        $request = Yii::$app->request;

        if($model->load($request->post())) {
            $returnUrl = Shopcart::send($model->attributes) ? $request->post('successUrl') : $request->post('errorUrl');
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}