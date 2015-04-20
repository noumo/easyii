<?php
namespace yii\easyii\modules\article\api;

use Yii;

use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;

class Article extends \yii\easyii\components\API
{
    private $_cats;
    private $_items;
    private $_last;

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

        $query = Item::find()->with('seo')->status(Item::STATUS_ON)->sort()->limit($limit);
        if($where){
            $query->where($where);
        }

        foreach($query->all() as $item){
            $result[] = new ArticleObject($item);
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

    private function findCategory($id_slug)
    {
        $category = Category::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $category ? new CategoryObject($category) : null;
    }

    private function findItem($id_slug)
    {
        $article = Item::find()->where(['or', 'item_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();
        if($article) {
            $article->updateCounters(['views' => 1]);
            return new ArticleObject($article);
        } else {
            return null;
        }
    }
}