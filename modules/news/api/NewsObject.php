<?php
namespace yii\easyii\modules\news\api;

use yii\easyii\components\API;
use yii\helpers\Url;

class NewsObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $views;
    public $time;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getShort(){
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink) : $this->model->short;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function  getEditLink(){
        return Url::to(['/admin/news/a/edit/', 'id' => $this->id]);
    }
}