<?php
namespace yii\easyii\modules\catalog\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;

use yii\easyii\widgets\Colorbox;
use yii\easyii\models\Photo;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;

class Catalog extends \yii\easyii\components\API
{
    private $_cats;
    private $_items;
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

    public function api_item($id_slug)
    {
        if(!isset($this->_items[$id_slug])) {
            $this->_items[$id_slug] = $this->findItem($id_slug);
        }
        return $this->_items[$id_slug];
    }

    public function api_photo($id)
    {
        $photo = Photo::findOne($id);
        return $photo ? $photo->image : null;
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    public function api_colorbox($options = [])
    {
        Colorbox::widget([
            'selector' => '.easyii-box',
            'options' => $options
        ]);
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
            $query->andWhere($this->_albumsOptions['where']);
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
        if(!($item = Item::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one())){
            return null;
        }

        $itemObject = $this->parseItem($item);
        $itemObject->photos = $this->parsePhotos($item->photos);

        return $itemObject;
    }

    private function parseCategory($category)
    {
        if(LIVE_EDIT){
            $category->title = $this->wrapLiveEdit($category->title, $category->primaryKey);
        }
        return $this->createCatObject($category->attributes);
    }

    private function parseItem($item)
    {
        if(LIVE_EDIT){
            $item->title = $this->wrapLiveEdit($item->title, $item->primaryKey);
            $item->description = $this->wrapLiveEdit($item->description, $item->primaryKey, ['tag' => 'div']);
        }
        return $this->createItemObject($item->attributes);
    }

    private function parsePhotos($photos)
    {
        $result = [];

        foreach($photos as $photo)
        {
            $temp = new \stdClass();
            $temp->id = $photo->primaryKey;
            $temp->image = $photo->image;
            $temp->thumb = $photo->thumb;
            $temp->box = '<a class="easyii-box" href="'.$photo->image.'" rel="album-'.$photo->item_id.'" title="'.$photo->description.'"><img src="'.$photo->thumb.'"></a>';

            if(LIVE_EDIT){
                $temp->box = $this->wrapLiveEdit($temp->box, $photo->item_id, [
                    'hash' => 'photo-'.$photo->primaryKey
                ]);
            }

            $result[] = $temp;
        }
        return $result;
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
        $temp = (object)[
            'id' => $data['category_id'],
            'slug' => $data['slug'],
            'title' => $data['title'],
            'thumb' => $data['thumb'],
            'description' => $data['description'],
        ];

        foreach($data['data'] as $key => $value){
            $temp->{$key} = $value;
        }
        return $temp;
    }

    private function notFound($id_slug)
    {
        if(Yii::$app->user->isGuest) {
            return $this->createCatObject('');
        }
        elseif(preg_match('/^[a-zA-Z][\w_-]*$/', $id_slug)){
            return $this->createCatObject('<a href="/admin/catalog/a/create/?slug='.$id_slug.'" target="_blank">'.Yii::t('easyii/catalog/api', 'Create category').'</a>');
        }
        elseif(is_numeric($id_slug)){
            return $this->createCatObject($this->errorText('WRONG CATEGORY_ID'));
        }
        else{
            return $this->createCatObject($this->errorText('WRONG CATEGORY_SLUG'));
        }
    }
}