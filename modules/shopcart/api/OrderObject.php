<?php
namespace yii\easyii\modules\shopcart\api;

use yii\easyii\modules\shopcart\models\Good;

class OrderObject extends \yii\easyii\components\ApiObject
{
    public $name;
    public $address;
    public $phone;
    public $email;
    public $access_token;

    private $_goods;
    private $_cost;

    public function getGoods()
    {
        if(!$this->_goods){
            $this->_goods = [];
            if($this->id){
                foreach(Good::find()->where(['order_id' => $this->id])->with('item')->all() as $good){
                    $this->_goods[] = new GoodObject($good);
                }
            }
        }
        return $this->_goods;
    }

    public function getCost()
    {
        if($this->_cost === null) {
            $this->_cost = 0;
            foreach ($this->goods as $good) {
                $this->_cost += $good->price * $good->count;
            }
        }
        return $this->_cost;
    }

    public function getStatus()
    {
        return $this->model->statusName;
    }
}