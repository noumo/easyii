<?php
namespace yii\easyii\behaviors;

use Yii;

class SortableControllerNS extends \yii\base\Behavior
{
    public $model;

    public function move($id, $direction)
    {
        $modelClass = $this->model;

        if(($model = $modelClass::findOne($id)))
        {
            $up = $direction == 'up';
            $orderDir = $up ? 'ASC' : 'DESC';

            if($model->primaryKey == $model->tree){
                $swapCat = $modelClass::find()->where([$up ? '>' : '<', 'order_num', $model->order_num])->orderBy('order_num '.$orderDir)->one();
                if($swapCat)
                {
                    $modelClass::updateAll(['order_num' => '-1'], ['order_num' => $swapCat->order_num]);
                    $modelClass::updateAll(['order_num' => $swapCat->order_num], ['order_num' => $model->order_num]);
                    $modelClass::updateAll(['order_num' => $model->order_num], ['order_num' => '-1']);
                }
            } else {
                $where = [
                    'and',
                    ['tree' => $model->tree],
                    ['depth' => $model->depth],
                    [($up ? '<' : '>'), 'lft', $model->lft]
                ];

                $swapCat = $modelClass::find()->where($where)->orderBy(['lft' => ($up ? SORT_DESC : SORT_ASC)])->one();
                if($swapCat)
                {
                    if($up) {
                        $model->insertBefore($swapCat);
                    } else {
                        $model->insertAfter($swapCat);
                    }

                    $swapCat->update();
                    $model->update();
                }
            }
        }
        else {
            $this->owner->flash('error', Yii::t('easyii', 'Not found'));
        }
        return $this->owner->back();
    }
}