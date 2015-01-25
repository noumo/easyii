<?php
namespace yii\easyii\behaviors;

use Yii;

class StatusController extends \yii\base\Behavior
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

        return $this->owner->formatResponse();
    }
}