<?php
namespace yii\easyii\modules\gallery\api;

use yii\data\ActiveDataProvider;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use yii\easyii\modules\gallery\models\Album;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $tree;
    public $depth;

    private $_adp;
    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getPages(){
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    public function getPagination(){
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function photos($options = [])
    {
        if(!$this->_photos){
            $this->_photos = [];

            $this->_adp = new ActiveDataProvider([
                'query' => Photo::find()->where(['model' => Album::className(), 'item_id' => $this->id])->sort(),
                'pagination' => $options
            ]);

            foreach($this->_adp->models as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/edit/', 'id' => $this->id]);
    }
}