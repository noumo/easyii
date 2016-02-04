<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\helpers\Image;

/**
 * Class ApiObject
 * @package yii\easyii\components
 * @var integer $id
 * @var string $image
 */
class ApiObject extends \yii\base\Object
{
    /** @var \yii\base\Model  */
    public $model;

    /**
     * Generates ApiObject, attaching all settable properties to the child object
     * @param \yii\base\Model $model
     */
    public function __construct($model){
        if($model) {
            $this->model = $model;

            foreach ($model->attributes as $attribute => $value) {
                if ($this->canSetProperty($attribute)) {
                    $this->{$attribute} = $value;
                }
            }
        } else {
            $this->model = new \stdClass();
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
    public function getId($debug = false){

        return $this->model->primaryKey;
    }

    /**
     * Creates thumb from model->image attribute with specified width and height.
     * @param int|null $width
     * @param int|null $height
     * @param bool $crop if false image will be resize instead of cropping
     * @return string
     */
    public function thumb($width = null, $height = null)
    {
        return !empty($this->model->image_file) ? Image::thumb($this->model->image_file, $width, $height) : '';
    }

    /**
     * Returns web path to image.
     * @return string
     */
    public function getImage()
    {
        return !empty($this->model->image_file) ? $this->model->image : '';
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