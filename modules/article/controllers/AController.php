<?php
namespace yii\easyii\modules\article\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii\components\Controller;
use yii\easyii\modules\article\models\Category;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\SortableController;
use yii\easyii\behaviors\StatusController;


class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Category::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Category::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Category::findWithItemCount()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new Category;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'article', $this->module->settings['categoryThumbWidth'], $this->module->settings['categoryThumbHeight'], $this->module->settings['categoryThumbCrop']);
                    }else{
                        $model->thumb = '';
                    }
                }

                $model->status = Category::STATUS_ON;

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/article', 'Category created'));
                    return $this->redirect(['/admin/article/items/index', 'id' => $model->primaryKey]);
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            if($slug) $model->slug = $slug;

            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/article/']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'article', $this->module->settings['categoryThumbWidth'], $this->module->settings['categoryThumbHeight'], $this->module->settings['categoryThumbCrop']);
                    }else{
                        $model->thumb = $model->oldAttributes['thumb'];
                    }
                }
                if($model->save()){
                    $this->flash('success', Yii::t('easyii/article', 'Category updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionClearImage($id)
    {
        $model = Category::findOne($id);

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
        if(($model = Category::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/article', 'Category deleted'));
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Category::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Category::STATUS_OFF);
    }
}