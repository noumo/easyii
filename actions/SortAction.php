<?php
namespace yii\easyii\actions;

class SortAction extends \yii\base\Action
{
    public $model;
    public $attribute;
    public $direction;

    public function run($id)
    {
        $modelClass = $this->model;
        $attribute = $this->attribute;

        if(!$this->direction){
            $this->direction = $this->id;
        }
        $success = '';
        if(($model = $modelClass::findOne($id))) {
            if($this->direction === 'up') {
                $eq = '>';
                $orderDir = SORT_ASC;
            } else {
                $eq = '<';
                $orderDir = SORT_DESC;
            }
            $modelSwap = $modelClass::find()->where([$eq, $attribute, $model->{$attribute}])->orderBy([$attribute => $orderDir])->limit(1)->one();

            if(!empty($modelSwap)) {
                $newValue = $modelSwap->{$attribute};

                $modelSwap->{$attribute} = $model->{$attribute};
                $modelSwap->update();

                $model->{$attribute} = $newValue;
                $model->update();

                $success = ['swap_id' => $modelSwap->primaryKey];
            }
        } else {
            $this->controller->error = \Yii::t('easyii', 'Not found');
        }

        return $this->controller->formatResponse($success);
    }
}