<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\helpers\Image;

class ApiObject extends \yii\base\Object
{
    public $model;

    /**
     * Generates ApiObject, attaching all settable properties to the child object
     * @param \stdClass $model yii\base\Model object
     */
    public function __construct($model){
        $this->model = $model;

        foreach($model->attributes as $attribute => $value){
            if($this->canSetProperty($attribute)){
                $this->{$attribute} = $value;
            }
        }

        $this->init();
    }

    /**
     * calls after __construct
     */
    public function init(){}

    /**
     * Returns object id
     * @return int
     */
    public function getId(){
        return $this->model->primaryKey;
    }

    /**
     * Creates thumb from model->image attribute with specified width and height.
     * @param int|null $width
     * @param int|null $height
     * @param bool $crop if false image will be resize instead of cropping
     * @return string
     */
    public function thumb($width = null, $height = null, $crop = true)
    {
        if($this->image && ($width || $height)){
            return Image::thumb($this->image, $width, $height, $crop);
        }
        return '';
    }

    /**
     * Get seo text attached to object
     * @param string $attribute name of seo attribute can be h1, title, description, keywords
     * @param string $default default string applied if seo text not found
     * @return string
     */
    public function seo($attribute, $default = ''){
        return !empty($this->model->seo->{$attribute}) ? $this->model->seo->{$attribute} : $default;
    }
}