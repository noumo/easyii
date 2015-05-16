<?php
namespace yii\easyii\modules\shopcart\api;

use yii\easyii\modules\catalog\api\ItemObject;

class GoodObject extends \yii\easyii\components\ApiObject
{
    public $order_id;
    public $item_id;
    public $options;
    public $discount;
    public $count;

    private $_item;

    public function getItem()
    {
        if(!$this->_item){
            $this->_item = new ItemObject($this->model->item);
        }
        return $this->_item;
    }

    public function getPrice(){
        return round($this->model->price * (1 - $this->discount / 100), 2);
    }

    public function getOld_price(){
        return $this->discount ? $this->model->price : null;
    }

    public function getCategory_id()
    {
        return $this->item->category_id;
    }

    public function getSlug()
    {
        return $this->item->slug;
    }
}