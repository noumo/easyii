<?php
namespace yii\easyii\actions;

class BaseSortAction extends \yii\base\Action
{
    public $model;
    public $attribute;
    public $direction;
    public $addititonalEquality = [];

    public function run($id)
    {
        $modelClass = $this->model ? $this->model : $this->controller->modelClass;
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

            $query = $modelClass::find()->orderBy([$attribute => $orderDir])->limit(1);

            $where = [$eq, $attribute, $model->{$attribute}];
            if (count($this->addititonalEquality)) {
                $where = ['and', $where];
                foreach ($this->addititonalEquality as $item) {
                    $where[] = [$item => $model->{$item}];
                }
            }
            $modelSwap = $query->where($where)->one();

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