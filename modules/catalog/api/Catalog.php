<?php
namespace yii\easyii\modules\catalog\api;

use Yii;

use yii\easyii\widgets\Fancybox;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;

class Catalog extends \yii\easyii\components\API
{
    private $_cats;
    private $_items;

    public function api_cat($id_slug)
    {
        if(!isset($this->_cats[$id_slug])) {
            $this->_cats[$id_slug] = $this->findCategory($id_slug);
        }
        return $this->_cats[$id_slug];
    }

    public function api_tree()
    {
        return Category::tree();
    }

    public function api_last($limit = 1, $where = null)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];

        $query = Item::find()->with('seo')->sort()->limit($limit);
        if($where){
            $query->where($where);
        }

        foreach($query->all() as $item){
            $result[] = new ItemObject($item);
        }

        if($limit > 1){
            return $result;
        }else{
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
    }

    public function api_get($id_slug)
    {
        if(!isset($this->_items[$id_slug])) {
            $this->_items[$id_slug] = $this->findItem($id_slug);
        }
        return $this->_items[$id_slug];
    }

    public function api_plugin($options = [])
    {
        Fancybox::widget([
            'selector' => '.easyii-box',
            'options' => $options
        ]);
    }

    private function findCategory($id_slug)
    {
        $category = Category::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $category ? new CategoryObject($category) : null;
    }

    private function findItem($id_slug)
    {
        if(!($item = Item::find()->where(['or', 'item_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one())){
            return null;
        }

        return new ItemObject($item);
    }
}