<?php
namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Url;

class PageObject extends \yii\easyii\components\ApiObject
{
    public $data;
    public $slug;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getEditLink(){
        return Url::to(['/admin/page/a/edit/', 'id' => $this->id]);
    }

    public function __get($name)
    {
        if(is_object($this->data) && property_exists($this->data, $name)){
            return $this->data->{$name};
        }
        return parent::__get($name);
    }

    public function __isset($name)
    {
        if(is_object($this->data) && property_exists($this->data, $name)){
            return true;
        }
        return parent::__isset($name);
    }
}