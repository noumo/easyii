<?php
namespace yii\easyii\behaviors;

use Yii;

/**
 * Status behavior. Adds statuses to models
 * @package yii\easyii\behaviors
 */
class CommonActions extends \yii\base\Behavior
{
    public $model;

    public function changeStatus($id, $status)
    {
        $modelClass = $this->model;

        if(($model = $modelClass::findOne($id))){
            $model->status = $status;
            $model->update();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->owner->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }

    public function clearImage($id)
    {
        $modelClass = $this->model;
        $model = $modelClass::findOne($id);

        if($model === null){
            $this->owner->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->image_file){
            $model->image_file = '';
            if($model->update()){
                $this->owner->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->owner->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->owner->back();
    }

    public function deleteModel($id, $successMessage = 'Deleted')
    {
        $modelClass = $this->model;
        if(($model = $modelClass::findOne($id))){
            $model->delete();
        } else {
            $this->owner->error = Yii::t('easyii', 'Not found');
        }
        return $this->owner->formatResponse($successMessage);
    }

    public function moveByTime($id, $direction, $condition = [])
    {
        $modelClass = $this->model;
        $success = '';
        if(($model = $modelClass::findOne($id))){
            if($direction === 'up'){
                $eq = '>';
                $orderDir = 'ASC';
            } else {
                $eq = '<';
                $orderDir = 'DESC';
            }

            $query = $modelClass::find()->orderBy('time '.$orderDir)->limit(1);

            $where = [$eq, 'time', $model->time];
            if(count($condition)){
                $where = ['and', $where];
                foreach($condition as $key => $value){
                    $where[] = [$key => $value];
                }
            }
            $modelSwap = $query->where($where)->one();

            if(!empty($modelSwap))
            {
                $newOrderNum = $modelSwap->time;

                $modelSwap->time = $model->time;
                $modelSwap->update();

                $model->time = $newOrderNum;
                $model->update();

                $success = ['swap_id' => $modelSwap->primaryKey];
            }
        }
        else{
            $this->owner->error = Yii::t('easyii', 'Not found');
        }

        return $this->owner->formatResponse($success);
    }

    public function moveByNum($id, $direction, $condition = [])
    {
        $modelClass = $this->model;
        $success = '';
        if (($model = $modelClass::findOne($id))) {
            if ($direction === 'up') {
                $eq = '>';
                $orderDir = 'ASC';
            } else {
                $eq = '<';
                $orderDir = 'DESC';
            }

            $query = $modelClass::find()->orderBy('order_num ' . $orderDir)->limit(1);

            $where = [$eq, 'order_num', $model->order_num];
            if (count($condition)) {
                $where = ['and', $where];
                foreach ($condition as $key => $value) {
                    $where[] = [$key => $value];
                }
            }
            $modelSwap = $query->where($where)->one();

            if (!empty($modelSwap)) {
                $newOrderNum = $modelSwap->order_num;

                $modelSwap->order_num = $model->order_num;
                $modelSwap->update();

                $model->order_num = $newOrderNum;
                $model->update();

                $success = ['swap_id' => $modelSwap->primaryKey];
            }
        } else {
            $this->owner->error = Yii::t('easyii', 'Not found');
        }

        return $this->owner->formatResponse($success);
    }
}