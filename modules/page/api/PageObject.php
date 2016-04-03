<?php
namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Url;

class PageObject extends \yii\easyii\components\ApiObject
{
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
}