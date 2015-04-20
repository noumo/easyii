<?php
namespace yii\easyii\modules\catalog\api;

use yii\data\ActiveDataProvider;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use yii\easyii\modules\catalog\models\Item;
use yii\helpers\Url;

class ItemObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $views;

    private $_adp;
    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function photos($options = [])
    {
        if(!$this->_photos){
            $this->_photos = [];

            $query = Photo::find()->where(['model' => Item::className(), 'item_id' => $this->id])->sort();

            if(!empty($options['where'])){
                $query->where($options['where']);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function getEditLink(){
        return Url::to(['/admin/catalog/items/edit/', 'id' => $this->id]);
    }
}