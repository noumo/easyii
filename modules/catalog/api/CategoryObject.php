<?php
namespace yii\easyii\modules\catalog\api;

use yii\data\ActiveDataProvider;
use yii\easyii\components\API;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\catalog\models\ItemData;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $tree;
    public $fields;
    public $depth;

    private $_adp;
    private $_items;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function pages($options = []){
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    public function pagination(){
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $query = Item::find()->with('seo')->where(['category_id' => $this->id])->status(Item::STATUS_ON);

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['orderBy'])){
                $query->orderBy($options['orderBy']);
            } else {
                $query->sortDate();
            }

            if(!empty($options['filters']) && is_array($options['filters'])){

                if(!empty($options['filters']['price'])){
                    $price = $options['filters']['price'];
                    if(is_array($price) && count($price) == 2) {
                        if(!$price[0]){
                            $query->andFilterWhere(['<=', 'price', (float)$price[1]]);
                        } elseif(!$price[1]) {
                            $query->andFilterWhere(['>=', 'price', (float)$price[0]]);
                        } else {
                            $query->andFilterWhere(['between', 'price', (float)$price[0], (float)$price[1]]);
                        }
                    }
                    unset($options['filters']['price']);
                }
                if(count($options['filters'])){
                    $filtersApplied = 0;
                    $subQuery = ItemData::find()->select('item_id, COUNT(*) as filter_matched')->groupBy('item_id');
                    foreach($options['filters'] as $field => $value){
                        if(!is_array($value)) {
                            $subQuery->orFilterWhere(['and', ['name' => $field], ['value' => $value]]);
                            $filtersApplied++;
                        } elseif(count($value) == 2){
                            if(!$value[0]){
                                $additionalCondition = ['<=', 'value', (float)$value[1]];
                            } elseif(!$value[1]) {
                                $additionalCondition = ['>=', 'value', (float)$value[0]];
                            } else {
                                $additionalCondition = ['between', 'value', (float)$value[0], (float)$value[1]];
                            }
                            $subQuery->orFilterWhere(['and', ['name' => $field], $additionalCondition]);

                            $filtersApplied++;
                        }
                    }
                    if($filtersApplied) {
                        $query->join('LEFT JOIN', ['f' => $subQuery], 'f.item_id = '.Item::tableName().'.item_id');
                        $query->andFilterWhere(['f.filter_matched' => $filtersApplied]);
                    }
                }
            }
            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new ItemObject($model);
            }
        }
        return $this->_items;
    }

    public function fieldOptions($name, $firstOption = '')
    {
        $options = [];
        if($firstOption) {
            $options[''] = $firstOption;
        }

        foreach($this->fields as $field){
            if($field->name == $name){
                foreach($field->options as $option){
                    $options[$option] = $option;
                }
                break;
            }
        }
        return $options;
    }

    public function getEditLink(){
        return Url::to(['/admin/catalog/a/edit/', 'id' => $this->id]);
    }
}