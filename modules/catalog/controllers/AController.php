<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\easyii\components\CategoryController;
use yii\easyii\modules\catalog\models\Category;


class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\catalog\models\Category';
    public $moduleName = 'catalog';

    public $rootActions = ['fields'];

    public function actionFields($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
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
                    !array_key_exists($temp->type, Category::$fieldTypes)
                ){
                    continue;
                }
                $options = '';
                if($temp->type == 'select' || $temp->type == 'checkbox'){
                    if(empty($temp->options) || !($temp->options = trim($temp->options))){
                        continue;
                    }
                    $options = [];
                    foreach(explode(',', $temp->options) as $option){
                        $options[] = trim($option);
                    }
                }

                $result[] = [
                    'name' => \yii\helpers\Inflector::slug($temp->name),
                    'title' => $temp->title,
                    'type' => $temp->type,
                    'options' => $options
                ];
            }

            $model->fields = $result;

            if($model->save()){
                $ids = [];
                foreach($model->children()->all() as $child){
                    $ids[] = $child->primaryKey;
                }
                if(count($ids)){
                    Category::updateAll(['fields' => json_encode($model->fields)], ['in', 'category_id', $ids]);
                }

                $this->flash('success', Yii::t('easyii/catalog', 'Category updated'));
            }
            else{
                $this->flash('error', Yii::t('easyii','Update error. {0}', $model->formatErrors()));
            }
            return $this->refresh();
        }
        else {
            return $this->render('fields', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $this->view->params['submenu'] = true;

        return parent::actionEdit($id);
    }
}