<?php
namespace yii\easyii\modules\faq\api;

use yii\easyii\components\API;
use yii\helpers\Url;

class FaqObject extends \yii\easyii\components\ApiObject
{
    public function getQuestion(){
        return LIVE_EDIT ? API::liveEdit($this->model->question, $this->editLink) : $this->model->question;
    }

    public function getAnswer(){
        return LIVE_EDIT ? API::liveEdit($this->model->answer, $this->editLink) : $this->model->answer;
    }

    public function getTags(){
        return $this->model->tagsArray;
    }

    public function  getEditLink(){
        return Url::to(['/admin/faq/a/edit/', 'id' => $this->id]);
    }
}