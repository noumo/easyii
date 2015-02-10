<?php
namespace yii\easyii\modules\gallery\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\helpers\Image;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use yii\easyii\modules\gallery\models\Album;
use yii\easyii\behaviors\SortableController;
use yii\easyii\behaviors\StatusController;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Album::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Album::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Album::findWithPhotoCount()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new Album;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['albumThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'gallery', $this->module->settings['albumThumbWidth'], $this->module->settings['albumThumbHeight'], $this->module->settings['albumThumbCrop']);
                    }else{
                        $model->thumb = '';
                    }
                }
                if($model->save()){
                    $this->flash('success', Yii::t('easyii/gallery', 'Album created'));
                    return $this->redirect('/admin/gallery/a/photos/'.$model->primaryKey);
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            if($slug){
                $model->slug = $slug;
            }
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Album::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect('/admin/gallery');
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['albumThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'catalog', $this->module->settings['albumThumbWidth'], $this->module->settings['albumThumbHeight'], $this->module->settings['albumThumbCrop']);
                    }else{
                        $model->thumb = $model->oldAttributes['thumb'];
                    }
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/gallery', 'Album updated'));
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

    public function actionPhotos($id)
    {
        if(!($model = Album::findOne($id))){
            return $this->redirect('/admin/gallery');
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionClearImage($id)
    {
        $model = Album::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->thumb){
            @unlink(Yii::getAlias('@webroot').$model->thumb);
            $model->thumb = '';
            $model->update();
            $this->flash('success', Yii::t('easyii/gallery', 'Category image cleared'));
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Album::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/gallery', 'Album deleted'));
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
        return $this->changeStatus($id, Album::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Album::STATUS_OFF);
    }
}