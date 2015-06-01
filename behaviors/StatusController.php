<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\base\Behavior;
use yii\easyii\components\ActiveRecord;

/**
 * Status behavior. Adds statuses to models
 * @package yii\easyii\behaviors
 */
class StatusController extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
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