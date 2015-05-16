<?php
namespace yii\easyii\modules\shopcart\api;

use Yii;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\shopcart\models\Good;
use yii\easyii\modules\shopcart\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Shopcart extends \yii\easyii\components\API
{
    const SENT_VAR = 'shopcart_sent';

    private $_order;
    private $_items;

    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_items()
    {
        return $this->items;
    }

    public function api_order()
    {
        return new OrderObject($this->order);
    }

    public function api_form($options = [])
    {
        $model = new Order;
        $model->scenario = 'confirm';
        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
            'action' => Url::to(['/admin/shopcart/send'])
        ]);

        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

        echo $form->field($model, 'name');
        echo $form->field($model, 'address');

        if($settings['enableEmail']) echo $form->field($model, 'email');
        if($settings['enablePhone']) echo $form->field($model, 'phone');

        echo $form->field($model, 'comment')->textarea();

        echo Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_add($item_id, $count = 1, $options = '', $increaseOnDublicate = true)
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
        if($good->order_id != $this->order->order_id){
            return ['result' => 'error', 'code' => 2, 'error' => 'Access denied'];
        }

        $good->delete();

        return ['result' => 'success', 'good_id' => $good_id, 'order_id' => $good->order_id];
    }

    public function api_update($goods)
    {
        if(is_array($goods) && count($this->items)) {
            foreach($this->items as $good){
                if(!empty($goods[$good->id]))
                {
                    $count = (int)$goods[$good->id];
                    if($count > 0){
                        $good->model->count = $count;
                        $good->model->update();
                    }
                }
            }
        }
    }

    public function api_send($data)
    {
        if($this->order->isNewRecord || $this->order->status != Order::STATUS_BLANK){
            return ['result' => 'error', 'code' => 1, 'error' => 'Order not found'];
        }
        if(!count($this->order->goods)){
            return ['result' => 'error', 'code' => 2, 'error' => 'Order is empty'];
        }
        $this->order->setAttributes($data);
        $this->order->status = Order::STATUS_PENDING;
        if($this->order->save()){
            return [
                'result' => 'success',
                'order_id' => $this->order->primaryKey,
                'access_token' => $this->order->access_token
            ];
        } else {
            return ['result' => 'error', 'code' => 3, 'error' => $this->order->formatErrors()];
        }
    }

    public function api_cost()
    {
        $cost = 0;
        if(count($this->items)){
            foreach($this->items as $good){
                $cost += $good->price * $good->count;
            }
        }
        return $cost;
    }

    public function getItems()
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

    public function getOrder()
    {
        if(!$this->_order){
            $access_token = $this->token;

            if(!$access_token || !($this->_order = Order::find()->where(['access_token' => $access_token])->status(Order::STATUS_BLANK)->one())){
                $this->_order = new Order();
            }
        }
        return $this->_order;
    }

    public function getToken(){
        return Yii::$app->session->get(Order::SESSION_KEY);
    }
}