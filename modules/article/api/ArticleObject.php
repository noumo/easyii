<?php
namespace yii\easyii\modules\article\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use yii\easyii\modules\article\models\Item;
use yii\helpers\Url;

class ArticleObject extends \yii\easyii\components\ApiObject
{
    /** @var  string */
    public $slug;

    public $image;

    public $views;

    public $time;

    /** @var  int */
    public $category_id;

    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getShort(){
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink, 'div') : $this->model->short;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getInclude_Image(){
        return LIVE_EDIT ? API::liveEdit($this->model->include_image, $this->editLink) : $this->model->include_image;
    }

    public function getInclude_Short(){
        return LIVE_EDIT ? API::liveEdit($this->model->include_short, $this->editLink) : $this->model->include_short;
    }

    public function getShow_On_Front_Page(){
        return LIVE_EDIT ? API::liveEdit($this->model->show_on_front_page, $this->editLink) : $this->model->show_on_front_page;
    }

    public function getCat(){
        return Article::cats()[$this->category_id];
    }

    public function getTags(){
        return $this->model->tagsArray;
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->time);
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
        return Url::to(['/admin/article/items/edit/', 'id' => $this->id]);
    }
}