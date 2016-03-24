<?php
namespace yii\easyii\modules\entity\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use yii\easyii\modules\entity\models\Item;
use yii\helpers\Url;

class ItemObject extends \yii\easyii\components\ApiObject
{
    public $data;
    public $category_id;

    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getCat(){
        return Entity::cat($this->category_id);
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
        return Url::to(['/admin/entity/items/edit/', 'id' => $this->id]);
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