<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\helpers\Image;

class ApiObject extends \yii\base\Object
{
    public $model;

    public function __construct($model){
        $this->model = $model;

        foreach($model->attributes as $attribute => $value){
            if($this->canSetProperty($attribute)){
                $this->{$attribute} = $value;
            }
        }

        $this->init();
    }

    public function init(){}

    public function getId(){
        return $this->model->primaryKey;
    }

    public function thumb($width = null, $height = null, $crop = true)
    {
        if($this->image && ($width || $height)){
            return Image::thumb($this->image, $width, $height, $crop);
        }
        return '';
    }

    public function seo($attribute, $default = ''){
        return !empty($this->model->seo->{$attribute}) ? $this->model->seo->{$attribute} : $default;
    }
}