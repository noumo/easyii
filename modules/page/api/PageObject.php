<?php
namespace yii\easyii\modules\page\api;

use yii\easyii\components\API;
use yii\helpers\Url;

class PageObject extends \yii\easyii\components\ApiObject
{
    public $data;
    public $slug;
    private $_children;
    private $_parent;

    public function getTitle($liveEditable = true){
        return ($liveEditable && LIVE_EDIT_ENABLED) ? API::liveEdit($this->model->title, $this->getEditLink()) : $this->model->title;
    }

    public function getText(){
        return LIVE_EDIT_ENABLED ? API::liveEdit($this->model->text, $this->getEditLink(), 'div') : $this->model->text;
    }

    public function getParent()
    {
        if($this->_parent === null) {
            $this->_parent = $this->model->parent ? Page::get($this->model->parent) : false;
        }
        return $this->_parent;
    }

    public function getChildren()
    {
        if($this->_children === null) {
            $this->_children = [];
            foreach ($this->model->children as $child) {
                $this->_children[] = Page::get($child);
            }
        }
        return $this->_children;
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