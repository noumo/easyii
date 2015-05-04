<?php
namespace yii\easyii\modules\shopcart\api;

use app\modules\shop\models\Item;
use Yii;
use yii\easyii\models\Setting;
use yii\easyii\modules\shopcart\models\Good;
use yii\easyii\modules\shopcart\models\Order;
use yii\helpers\Url;

class Shopcart extends \yii\easyii\components\API
{
    private $_order;
    private $_items;

    public function api_items()
    {
        if(!$this->_items){
            $this->_items = [];
            if(!$this->order->isNewRecord){
                foreach(Good::find()->where(['order_id' => $this->order->order_id])->with('item')->all() as $good){
                    $this->_items[] = new GoodObject($good);
                }
            }
        }
        return $this->_items;
    }

    public function api_order()
    {
        return new OrderObject($this->order);
    }

    public function api_add($item_id, $options = '', $count = 1, $increaseOnDublicate = true)
    {
        $shopItem = Item::findOne($item_id);
        if(!$shopItem){
            return ['result' => 'error', 'code' => 1, 'error' => 'Item no found'];
        }

        if($this->order->isNewRecord){
            if(!$this->order->save()){
                return ['result' => 'error', 'code' => 2, 'error' => 'Cannot create order. '.$this->order->formatErrors()];
            }
            Yii::$app->session->set(Order::SESSION_KEY, $this->order->access_token);
        }

        $good = Good::findOne([
            'order_id' => $this->order->primaryKey,
            'item_id' => $shopItem->primaryKey,
            'options' => $options
        ]);

        if($good && !$increaseOnDublicate){
            return ['result' => 'error', 'code' => 3, 'error' => 'Dublicate good in order.'];
        }

        if($good) {
            $good->count += $count;
        } else {
            $good = new Good([
                'order_id' => $this->order->primaryKey,
                'item_id' => $shopItem->primaryKey,
                'count' => (int)$count,
                'options' => $options,
                'discount' => $shopItem->discount,
                'price' => $shopItem->price
            ]);
        }

        if($good->save()){
           $response = [
               'result' => 'success',
               'order_id' => $good->order_id,
               'good_id' => $good->primaryKey,
               'item_id' => $shopItem->primaryKey,
               'options' => $good->options,
               'discount' => $good->discount,
           ];
            if($response['discount']){
                $response['price'] = round($good->price * (1 - $good->discount / 100));
                $response['old_price'] = $good->price;
            } else {
                $response['price'] = $good->price;
            }
            return $response;
        } else {
            return ['result' => 'error', 'code' => 4, 'error' => $good->formatErrors()];
        }
    }

    public function api_remove($good_id)
    {
        $good = Good::findOne($good_id);
        if(!$good){
            return ['result' => 'error', 'code' => 1, 'error' => 'Good not found'];
        }
        $order = $good->order;
        if($order->access_token != $this->token){
            return ['result' => 'error', 'code' => 2, 'error' => 'Access denied'];
        }

        $good->delete();

        return ['result' => 'success', 'good_id' => $good_id, 'order_id' => $order->primaryKey];
    }

    public function api_confirm($data)
    {
        if($this->order->isNewRecord || $this->order->status != Order::STATUS_NEW){
            return ['result' => 'error', 'code' => 1, 'error' => 'Order not found'];
        }
        if(!count($this->order->goods)){
            return ['result' => 'error', 'code' => 2, 'error' => 'Order is empty'];
        }
        $this->order->setAttributes($data);
        $this->order->status = Order::STATUS_PENDING;
        if($this->order->save()){
            if(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['mailAdminOnNewOrder']) {
                $this->api_mailAdmin($this->order->primaryKey);
            }
            return [
                'result' => 'success',
                'order_id' => $this->order->primaryKey,
                'access_token' => $this->order->access_token
            ];
        } else {
            return ['result' => 'error', 'code' => 3, 'error' => $this->order->formatErrors()];
        }
    }

    public function api_mailAdmin($order_id)
    {
        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
        $template = $settings['templateOnNewOrder'];
        $subject = $settings['subjectOnNewOrder'];

        if($template && $subject)
        {
            Yii::$app->mailer->compose($template, ['order_id' => $order_id, 'link' => Url::to(['/admin/shopcart/a/view', 'id' => $order_id])])
                ->setFrom(Setting::get('robot_email'))
                ->setTo(Setting::get('admin_email'))
                ->setSubject($subject)
                ->send();
        }

    }

    public function getOrder()
    {
        if(!$this->_order){
            $access_token = $this->token;

            if(!$access_token || !($this->_order = Order::find()->where(['access_token' => $access_token])->status(Order::STATUS_NEW)->one())){
                $this->_order = new Order();
            }
        }
        return $this->_order;
    }

    public function getToken(){
        return Yii::$app->session->get(Order::SESSION_KEY);
    }
}