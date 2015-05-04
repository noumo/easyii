<?php
namespace yii\easyii\modules\shopcart\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use yii\easyii\components\Controller;
use yii\easyii\modules\shopcart\models\Good;
use yii\easyii\modules\shopcart\models\Order;

class AController extends Controller
{
    public $pending = 0;
    public $confirmed = 0;
    public $sent = 0;

    public function init()
    {
        parent::init();

        $this->pending = Order::find()->status(Order::STATUS_PENDING)->count();
        $this->confirmed = Order::find()->status(Order::STATUS_CONFIRMED)->count();
        $this->sent = Order::find()->status(Order::STATUS_SENT)->count();
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => Order::find()->with('goods')->status(Order::STATUS_PENDING)->asc(),
                'totalCount' => $this->pending
            ])
        ]);
    }

    public function actionConfirmed()
    {
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => Order::find()->with('goods')->status(Order::STATUS_CONFIRMED)->asc(),
                'totalCount' => $this->confirmed
            ])
        ]);
    }

    public function actionSent()
    {
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => Order::find()->with('goods')->status(Order::STATUS_SENT)->asc(),
                'totalCount' => $this->sent
            ])
        ]);
    }

    public function actionCompleted()
    {
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => Order::find()->with('goods')->status(Order::STATUS_COMPLETED)->desc()
            ])
        ]);
    }

    public function actionFails()
    {
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => Order::find()->with('goods')->where(['in', 'status', [Order::STATUS_DECLINED, Order::STATUS_ERROR, Order::STATUS_RETURNED, Order::STATUS_NEW]])->desc()
            ])
        ]);
    }

    public function actionView($id)
    {
        $order = Order::findOne($id);

        if($order === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if($order->new > 0){
            $order->new = 0;
            $order->update();
        }

        $goods = Good::find()->where(['order_id' => $order->primaryKey])->with('item')->asc()->all();

        return $this->render('view', [
            'order' => $order,
            'goods' => $goods
        ]);
    }

    public function actionDelete($id)
    {
        if(($model = Order::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/shopcart', 'Order deleted'));
    }

    public function actionStatus($id, $status)
    {
        if(($model = Order::findOne($id)) && array_key_exists($status, Order::states())){
            $model->status = $status;
            $model->save();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/shopcart', 'Order status changed'));
    }
}