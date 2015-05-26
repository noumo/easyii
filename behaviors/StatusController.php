<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Status behavior. Adds statuses to models
 * @package yii\easyii\behaviors
 */
class StatusController extends Behavior
{
    public $model;

    public function changeStatus($id, $status)
    {
        $modelClass = $this->model;

        if(($model = $modelClass::findOne($id))){
            $model->status = $status;
            $model->update();
        }
        else{
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->owner->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }
}