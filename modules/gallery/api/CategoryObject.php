<?php
namespace yii\easyii\modules\gallery\api;

use yii\data\ActiveDataProvider;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use yii\easyii\modules\gallery\models\Category;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $description;
    public $tree;
    public $depth;
    public $parent;

    private $_adp;
    private $_children;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getTags(){
        return $this->model->tagsArray;
    }

    public function getPages($options = []){
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    public function getPagination(){
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function getChildren()
    {
        if($this->_children === null) {
            $this->_children = [];
            foreach ($this->model->children as $child) {
                $this->_children[] = Gallery::cat($child);
            }
        }
        return $this->_children;
    }

    public function getPhotos($options = [])
    {
        $result = [];

        $query = Photo::find()->where(['class' => Category::className(), 'item_id' => $this->id])->sort();

        if(!empty($options['where'])){
            $query->andFilterWhere($options['where']);
        }

        $this->_adp = new ActiveDataProvider([
            'query' => $query,
            'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
        ]);

        foreach($this->_adp->models as $model){
            $result[] = new PhotoObject($model);
        }
        return $result;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/edit/', 'id' => $this->id]);
    }
}