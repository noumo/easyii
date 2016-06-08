<?php
namespace yii\easyii\modules\entity\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \yii\easyii\components\ApiObject implements \yii\easyii\components\IApiHtml
{
    public $description;

    public function box($width, $height){
        $img = Html::img($this->thumb($width, $height));
        $a = Html::a($img, $this->image, [
            'class' => 'easyii-box',
            'rel' => 'entity-'.$this->model->item_id,
            'title' => $this->description
        ]);
        return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/entity/items/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }

    public function toHtml()
    {
        return $this->box(null, null);
    }
}