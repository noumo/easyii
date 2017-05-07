<?php
namespace yii\easyii\modules\article\controllers;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByDateAction;
use yii\easyii\components\Controller;
use yii\easyii\modules\article\ArticleModule;
use yii\easyii\modules\article\models\Category;
use yii\easyii\modules\article\models\Item;
use yii\widgets\ActiveForm;

class ItemsController extends Controller
{
    public $modelClass = 'yii\easyii\modules\article\models\Item';
    public $categoryClass = 'yii\easyii\modules\article\models\Category';

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'successMessage' => Yii::t('easyii/article', 'Article deleted')
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
            'time' => time()
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/article', 'Article created'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit', 'id' => $model->primaryKey]);
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
                'cats' => $this->getCats()
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
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/article', 'Article updated'));
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
                'cats' => $this->getCats()
            ]);
        }
    }

    public function actionPhotos($id)
    {
        return $this->render('photos', [
            'model' => $this->findModel($id),
        ]);
    }

    private function getCats()
    {
        $result = [];
        foreach(Category::cats() as $cat){
            if(!count($cat->children) || ArticleModule::setting('itemsInFolder')) {
                $result[$cat->id] = $cat->title;
            }
        }
        return $result;
    }


}