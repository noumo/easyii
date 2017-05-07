<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByDateAction;
use yii\easyii\behaviors\Fields;
use yii\easyii\components\Controller;
use yii\easyii\modules\catalog\CatalogModule;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;
use yii\widgets\ActiveForm;

class ItemsController extends Controller
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
            'up' => [
                'class' => SortByDateAction::className(),
                'addititonalEquality' => ['category_id']
            ],
            'down' => [
                'class' => SortByDateAction::className(),
                'addititonalEquality' => ['category_id']
            ],
            'on' => ChangeStatusAction::className(),
            'off' => ChangeStatusAction::className(),
        ];
    }

    public function behaviors()
    {
        return [
            'fields' => Fields::className()
        ];
    }

    public function actionIndex($id)
    {
        return $this->render('index', [
            'category' => $this->findCategory($id)
        ]);
    }

    public function actionCreate($id)
    {
        $category = $this->findCategory($id);

        $model = new Item([
            'category_id' => $id,
            'time' => time(),
            'available' => 1
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

    public function actionPhotos($id)
    {
        return $this->render('photos', [
            'model' => $this->findModel($id),
        ]);
    }

    public function getSameCats($cat)
    {
        $result = [];
        $fieldsHash = md5(json_encode($cat->fields));
        foreach(Category::cats() as $cat){
            if(md5(json_encode($cat->fields)) == $fieldsHash && (!count($cat->children) || CatalogModule::setting('itemsInFolder'))) {
                $result[$cat->id] = $cat->title;
            }
        }
        return $result;
    }
}