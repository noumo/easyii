<?php
namespace yii\easyii\modules\page\api;

use yii\easyii\components\API;
use yii\helpers\Url;

class PageObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    private $_children;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
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
}