<?php
namespace yii\easyii\modules\content\api;

use Yii;
use yii\easyii\components\ApiObject;
use yii\easyii\models\Photo;
use yii\easyii\modules\content\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;

class ItemObject extends ApiObject
{
    public $slug;
    public $image;
    public $data;
    public $category_id;
    public $available;
    public $discount;
    public $time;

    private $_photos;

    public function getTitle(){
        if($this->model->isNewRecord){
            return $this->createLink;
        }

        $value = $this->placeholder($this->model->title);

        return $this->liveEdit($value);
    }

    public function getContent(){
        if($this->model->isNewRecord){
            return $this->createLink;
        }

        $value = $this->placeholder($this->model->content);

        return $this->liveEdit($value);
    }

    public function getHeader(){
        if($this->model->isNewRecord){
            return $this->createLink;
        }

        $value = $this->placeholder($this->model->header);

        return $this->liveEdit($value);
    }

    /**
     * @return LayoutObject
     */
    public function getLayout(){
        return Content::cat($this->category_id);
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
        return Url::to(['/admin/content/item/edit/', 'id' => $this->id]);
    }

    public function getCreateLink(){
        return Html::a(Yii::t('easyii/content/api', 'Create page'), ['/admin/content/item/new'], ['target' => '_blank']);
    }
}