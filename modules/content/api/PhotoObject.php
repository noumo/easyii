<?php
namespace yii\easyii\modules\content\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\components\ApiObject;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends ApiObject
{
    public $image;
    public $description;

    public function box($width, $height){
        $img = Html::img($this->thumb($width, $height));
        $a = Html::a($img, $this->image, [
            'class' => 'easyii-box',
            'rel' => 'catalog-'.$this->model->item_id,
            'title' => $this->description
        ]);
        return $this->liveEdit($a);
    }

    public function getEditLink(){
        return Url::to(['/admin/catalog/item/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
}