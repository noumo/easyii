<?php
namespace yii\easyii\modules\catalog\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \yii\easyii\components\ApiObject
{
    public $image;
    public $description;

    public function getBox($width, $height){
        $a = Html::a(Html::img($this->thumb($width, $height), $this->image, [
            'class' => 'easyii-box',
            'rel' => 'album-'.$this->id,
            'title' => $this->description
        ]));
        return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/catalog/items/photos/', 'id' => $this->id.'#'.'photo-'.$this->id]);
    }
}