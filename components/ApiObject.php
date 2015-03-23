<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\helpers\GD;
use yii\easyii\helpers\Image;
use yii\easyii\helpers\Upload;
use yii\helpers\FileHelper;
use yii\widgets\LinkPager;

class ApiObject extends \yii\base\Object
{
    public $source = [];
    public $adp;
    private $_seo;

    public function __construct($model){
        if(isset($model->seo)){
            $this->_seo = $model->seo;
        }

        foreach($model->attributes as $attribute => $value){
            if($this->canSetProperty($attribute)){
                $this->{$attribute} = $value;
            } else {
                $this->source[$attribute] = $value;
            }
        }
    }

    public function thumb($width = null, $height = null, $crop = true)
    {
        if($this->image && ($width || $height)){
            return Image::thumb(Yii::getAlias('@webroot') . $this->image, $width, $height, $crop);
        }
        return '';
    }

    public function getPages(){
        return $this->adp ? LinkPager::widget(['pagination' => $this->adp->pagination]) : '';
    }

    public function seo($attribute){
        return (!empty($this->_seo) && isset($this->_seo->{$attribute})) ? $this->_seo->{$attribute} : '';
    }
}