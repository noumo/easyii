<?php
namespace yii\easyii\modules\article\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;

use yii\easyii\widgets\Colorbox;
use yii\easyii\models\Photo;
use yii\easyii\modules\article\models\Category;
use yii\easyii\modules\article\models\Item;

class Article extends \yii\easyii\components\API
{
    private $_cats;
    private $_items;
    private $_last;
    private $_adp;
    private $_catOptions = [
        'pageSize' => 20,
    ];
    private $_catsOptions = [
        'pageSize' => 20,
        'where' => '',
    ];

    public function api_cat($id_slug, $options = [])
    {
        if(!isset($this->_cats[$id_slug])) {
            if (is_array($options) && count($options)) {
                $this->_catOptions =  array_merge($this->_catOptions, $options);
            }
            $this->_cats[$id_slug] = $this->findCategory($id_slug);
        }
        return $this->_cats[$id_slug];
    }

    public function api_cats($options = [])
    {
        if(is_array($options) && count($options)){
            $this->_catsOptions = array_merge($this->_catsOptions, $options);
        }

        return $this->findCategories();
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];
        foreach(Item::find()->status(Item::STATUS_ON)->sort()->limit($limit)->all() as $item){
            $result[] = $this->parseItem($item);
        }

        if($limit > 1){
            return $result;
        }else{
            $this->_last = $result[0];
            return $this->_last;
        }
    }

    public function api_item($id_slug)
    {
        if(!isset($this->_items[$id_slug])) {
            $this->_items[$id_slug] = $this->findItem($id_slug);
        }
        return $this->_items[$id_slug];
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    private function findCategory($id_slug)
    {
        $category = Category::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        if(!$category){
            return $this->notFound($id_slug);
        }

        if($category->status != Category::STATUS_ON){
            return Yii::$app->user->isGuest ? $this->createObject('') : $this->createObject($this->errorText('CATEGORY IS OFF'));
        }

        $this->_adp = new ActiveDataProvider([
            'query' => Item::find()->where(['category_id' => $category->primaryKey])->sort(),
            'pagination' => [
                'pageSize' => $this->_catOptions['pageSize']
            ]
        ]);

        $catObject = $this->parseCategory($category);

        $catObject->seo_h1 = $category->seo_h1;
        $catObject->seo_title = $category->seo_title;
        $catObject->seo_keywords = $category->seo_keywords;
        $catObject->seo_description = $category->seo_description;

        $catObject->items = [];

        foreach($this->_adp->models as $item){
            $catObject->items[] = $this->parseItem($item);
        }

        return $catObject;
    }

    private function findCategories()
    {
        $result = [];

        $query = Category::find()->status(Category::STATUS_ON)->sort();

        if($this->_catsOptions['where']){
            $query->andWhere($this->_catsOptions['where']);
        }

        $this->_adp = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->_catsOptions['pageSize']
            ]
        ]);

        foreach($this->_adp->models as $category){
            $result[] = $this->parseCategory($category);
        }
        return $result;
    }

    private function findItem($id_slug)
    {
        if(!($item = Item::find()->where(['or', 'item_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one())){
            return null;
        }

        $item->updateCounters(['views' => 1]);

        $itemObject = $this->parseItem($item);
        $itemObject->seo_h1 = $item->seo_h1;
        $itemObject->seo_title = $item->seo_title;
        $itemObject->seo_keywords = $item->seo_keywords;
        $itemObject->seo_description = $item->seo_description;

        return $itemObject;
    }

    private function parseCategory($category)
    {
        if(LIVE_EDIT){
            $category->title = $this->wrapLiveEdit($category->title, 'a/edit/'.$category->primaryKey);
        }
        return $this->createCatObject($category->attributes);
    }

    private function parseItem($item)
    {
        if(LIVE_EDIT){
            $item->title = $this->wrapLiveEdit($item->title, 'items/edit/'.$item->primaryKey);
            $item->short = $this->wrapLiveEdit($item->short, 'items/edit/'.$item->primaryKey, 'div');
            $item->text = $this->wrapLiveEdit($item->text, 'items/edit/'.$item->primaryKey, 'div');
        }
        return $this->createItemObject($item->attributes);
    }

    private function createCatObject($data)
    {
        $is_string = !is_array($data);

        return (object)[
            'id' => $is_string ? '' : $data['category_id'],
            'title' => $is_string ? $data : $data['title'],
            'slug' => $is_string ? '' : $data['slug'],
            'thumb' => $is_string ? '' : $data['thumb'],
            'empty' => $is_string ? true : false
        ];
    }

    private function createItemObject($data)
    {
        return (object)[
            'id' => $data['item_id'],
            'category' => $data['category_id'],
            'title' => $data['title'],
            'short' => $data['short'],
            'text' => $data['text'],
            'thumb' => $data['thumb'],
            'views' => $data['views'],
            'slug' => $data['slug'],
        ];
    }

    private function notFound($id_slug)
    {
        if(Yii::$app->user->isGuest) {
            return $this->createCatObject('');
        }
        elseif(preg_match(Category::$slugPattern, $id_slug)){
            return $this->createCatObject('<a href="/admin/article/a/create/?slug='.$id_slug.'" target="_blank">'.Yii::t('easyii/article/api', 'Create category').'</a>');
        }
        else{
            return $this->createCatObject($this->errorText('WRONG CATEGORY IDENTIFIER'));
        }
    }
}