<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByDateAction;
use yii\easyii\components\ItemsWithFieldsController;
use yii\easyii\modules\catalog\models\Item;
use yii\widgets\ActiveForm;

class ItemsController extends ItemsWithFieldsController
{
    public $modelClass = 'yii\easyii\modules\catalog\models\Item';
    public $categoryClass = 'yii\easyii\modules\catalog\models\Category';

    public function actions()
    {
        $className = Item::className();
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => $className,
                'successMessage' => Yii::t('easyii/catalog', 'Item deleted')
            ],
            'clear-image' => ClearImageAction::className(),
            'up' => SortByDateAction::className(),
            'down' => SortByDateAction::className(),
            'on' => ChangeStatusAction::className(),
            'off' => ChangeStatusAction::className(),
        ];
    }

    public function actionCreate($id)
    {
        $category = $this->findCategory($id);

        $model = new Item([
            'category_id' => $id,
            'time' => time()
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->data = $this->parseData($model);

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/catalog', 'Item created'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit/', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
                'dataForm' => $this->generateForm($category->fields),
                'cats' => $this->getSameCats($category)
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->data = $this->parseData($model);
                
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/catalog', 'Item updated'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model,
                'dataForm' => $this->generateForm($model->category->fields, $model->data),
                'cats' => $this->getSameCats($model->category)
            ]);
        }
    }
}