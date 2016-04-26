<?php
namespace yii\easyii\modules\guestbook\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Url;

class PostObject extends \yii\easyii\components\ApiObject
{
    public $image;
    public $time;

    public function getTitle($liveEditable = true){
        return ($liveEditable && LIVE_EDIT_ENABLED) ? API::liveEdit($this->model->title, $this->getEditLink()) : $this->model->title;
    }

    public function getName(){
        return LIVE_EDIT_ENABLED ? API::liveEdit($this->model->name, $this->getEditLink()) : $this->model->name;
    }

    public function getText(){
        return LIVE_EDIT_ENABLED ? API::liveEdit($this->model->text, $this->getEditLink(), 'div') : $this->model->text;
    }

    public function getAnswer(){
        return LIVE_EDIT_ENABLED ? API::liveEdit($this->model->answer, $this->getEditLink(), 'div') : $this->model->answer;
    }

    public function getDate(){
        return Yii::$app->formatter->asDatetime($this->time, 'medium');
    }

    public function getEditLink(){
        return Url::to(['/admin/guestbook/a/edit', 'id' => $this->id]);
    }
}