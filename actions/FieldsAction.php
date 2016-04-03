<?php
namespace yii\easyii\actions;

use Yii;
use yii\easyii\components\CategoryWithFieldsModel;

class FieldsAction extends \yii\base\Action
{
    public $model;

    public function run($id)
    {
        $modelClass = $this->model ? $this->model : $this->controller->modelClass;

        /** @var \yii\easyii\components\CategoryWithFieldsModel $model */
        if(!($model = $modelClass::findOne($id))){
            return $this->controller->redirect(['/admin/' . $this->controller->module->id]);
        }

        if (Yii::$app->request->post('save'))
        {
            $fields = Yii::$app->request->post('Field') ?: [];
            $result = [];

            foreach($fields as $field){
                $temp = json_decode($field);

                if( $temp === null && json_last_error() !== JSON_ERROR_NONE ||
                    empty($temp->name) ||
                    empty($temp->title) ||
                    empty($temp->type) ||
                    !($temp->name = trim($temp->name)) ||
                    !($temp->title = trim($temp->title)) ||
                    !array_key_exists($temp->type, CategoryWithFieldsModel::$FIELD_TYPES)
                ){
                    continue;
                }
                $options = trim($temp->options);
                if($temp->type == CategoryWithFieldsModel::FIELD_TYPE_SELECT || $temp->type == CategoryWithFieldsModel::FIELD_TYPE_CHECKBOX){
                    if($options == ''){
                        continue;
                    }
                    $optionsArray = [];
                    foreach(explode(',', $options) as $option){
                        $optionsArray[] = trim($option);
                    }
                    $options = $optionsArray;
                }

                $result[] = [
                    'name' => \yii\helpers\Inflector::slug($temp->name),
                    'title' => $temp->title,
                    'type' => $temp->type,
                    'options' => $options
                ];
            }

            $model->fields = $result;

            if($model->update(false, ['fields'])){
                $ids = [];
                foreach($model->children()->all() as $child){
                    $ids[] = $child->primaryKey;
                }
                if(count($ids)){
                    $modelClass::updateAll(['fields' => json_encode($model->fields)], ['in', 'id', $ids]);
                }

                $this->controller->flash('success', Yii::t('easyii', 'Category updated'));
            } elseif($model->hasErrors()) {
                $this->controller->flash('error', Yii::t('easyii','Update error. {0}', $model->formatErrors()));
            }
            return $this->controller->refresh();
        }
        else {
            return $this->controller->render('@easyii/views/category/fields', [
                'model' => $model
            ]);
        }
    }
}