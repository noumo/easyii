<?php
namespace yii\easyii\actions;

use Yii;

class ClearImageAction extends \yii\base\Action
{
    public $model;

    public function run($id)
    {
        $modelClass = $this->model ? $this->model : $this->controller->modelClass;
        $model = $modelClass::findOne($id);

        if($model === null){
            $this->controller->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->image_file){
            $model->image_file = '';
            if($model->update()){
                $this->controller->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->controller->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->controller->back();
    }
}