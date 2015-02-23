<?php
namespace yii\easyii\modules\article\controllers;

use Yii;
use yii\web\UploadedFile;

use yii\easyii\components\Controller;
use yii\easyii\modules\article\models\Category;
use yii\easyii\modules\article\models\Item;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\SortableController;
use yii\widgets\ActiveForm;

class ItemsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Item::className(),
            ]
        ];
    }

    public function actionIndex($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect('/admin/article');
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionCreate($id)
    {
        if(!($category = Category::findOne($id))){
            return $this->redirect('/admin/article');
        }

        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->category_id = $category->primaryKey;

                if (isset($_FILES) && $this->module->settings['itemThumb']) {
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if ($model->thumb && $model->validate(['thumb'])) {
                        $model->thumb = Image::upload($model->thumb, 'article', $this->module->settings['itemThumbWidth'], $this->module->settings['itemThumbHeight'], $this->module->settings['itemThumbCrop']);
                    } else {
                        $model->thumb = '';
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/article', 'Article created'));
                    return $this->redirect('/admin/article/items/edit/' . $model->primaryKey);
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
            ]);
        }
    }

    public function actionEdit($id)
    {
        if(!($model = Item::findOne($id))){
            return $this->redirect('/admin/article');
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                if (isset($_FILES) && $this->module->settings['itemThumb']) {
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if ($model->thumb && $model->validate(['thumb'])) {
                        $model->thumb = Image::upload($model->thumb, 'article', $this->module->settings['itemThumbWidth'], $this->module->settings['itemThumbHeight'], $this->module->settings['itemThumbCrop']);
                    } else {
                        $model->thumb = $model->oldAttributes['thumb'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/article', 'Article updated'));
                    return $this->redirect('/admin/article/items/edit/' . $model->primaryKey);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionClearImage($id)
    {
        $model = Item::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->thumb){
            $model->thumb = '';
            if($model->update()){
                $this->flash('success', Yii::t('easyii/article', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Item::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/article', 'Article deleted'));
    }

    public function actionUp($id, $category_id)
    {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

    public function actionDown($id, $category_id)
    {
        return $this->move($id, 'down', ['category_id' => $category_id]);
    }
}