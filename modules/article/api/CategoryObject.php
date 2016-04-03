<?php
namespace yii\easyii\modules\article\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\API;
use yii\easyii\models\Tag;
use yii\easyii\modules\article\ArticleModule;
use yii\easyii\modules\article\models\Item;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $description;
    public $image;
    public $tree;
    public $depth;

    private $_adp;
    private $_children;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
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
                $this->_children[] = Article::cat($child);
            }
        }
        return $this->_children;
    }

    public function getItems($options = [])
    {
        $result = [];

        $with = ['seo'];
        if(ArticleModule::setting('enableTags')){
            $with[] = 'tags';
        }

        $query = Item::find()->with(['seo'])->where(['category_id' => $this->id])->status(Item::STATUS_ON)->sortDate();

        if(!empty($options['where'])){
            $query->andFilterWhere($options['where']);
        }
        if(!empty($options['tags'])){
            $query
                ->innerJoinWith('tags', false)
                ->andWhere([Tag::tableName() . '.name' => (new Item())->filterTagValues($options['tags'])])
                ->addGroupBy('id');
        }
        if(!empty($options['orderBy'])){
            $query->orderBy($options['orderBy']);
        } else {
            $query->sortDate();
        }

        $this->_adp = new ActiveDataProvider([
            'query' => $query,
            'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
        ]);

        foreach($this->_adp->models as $model){
            $result[] = new ArticleObject($model);
        }
        return $result;
    }

    public function getEditLink(){
        return Url::to(['/admin/article/a/edit/', 'id' => $this->id]);
    }
}