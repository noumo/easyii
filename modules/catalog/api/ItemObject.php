<?php
namespace yii\easyii\modules\catalog\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use yii\easyii\modules\catalog\models\Item;
use yii\helpers\Url;

class ItemObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $data;
    public $category_id;
    public $available;
    public $discount;
    public $time;

    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getDescription(){
        return LIVE_EDIT ? API::liveEdit($this->model->description, $this->editLink, 'div') : $this->model->description;
    }

    public function getCat(){
        return Catalog::cat($this->category_id);
    }

    public function getPrice(){
        return $this->discount ? round($this->model->price * (1 - $this->discount / 100) ) : $this->model->price;
    }

    public function getOldPrice(){
        return $this->model->price;
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = [];

            foreach(Photo::find()->where(['class' => Item::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function getEditLink(){
        return Url::to(['/admin/catalog/items/edit/', 'id' => $this->id]);
    }

    public function __get($name)
    {
        if(is_object($this->data) && property_exists($this->data, $name)){
            return $this->data->{$name};
        }
        return parent::__get($name);
    }

    public function __isset($name)
    {
        if(is_object($this->data) && property_exists($this->data, $name)){
            return true;
        }
        return parent::__isset($name);
    }
}