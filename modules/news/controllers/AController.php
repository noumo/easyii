<?php
namespace yii\easyii\modules\news\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii\components\Controller;
use yii\easyii\modules\news\models\News;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\StatusController;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => News::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => News::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new News;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['enableThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'news', $this->module->settings['thumbWidth'], $this->module->settings['thumbHeight'], $this->module->settings['thumbCrop']);
                    }
                    else{
                        $model->thumb = '';
                    }
                }
                $model->status = News::STATUS_ON;

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/news', 'News created'));
                    return $this->redirect('/admin/news');
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = News::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect('/admin/news');
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['enableThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'news', $this->module->settings['thumbWidth'], $this->module->settings['thumbHeight'], $this->module->settings['thumbCrop']);
                    }
                    else{
                        $model->thumb = $model->oldAttributes['thumb'];
                    }
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/news', 'News updated'));
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

    public function actionDelete($id)
    {
        if(($model = News::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/news', 'News deleted'));
    }

    public function actionClearImage($id)
    {
        $model = News::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        else{
            $model->thumb = '';
            if($model->update()){
                @unlink(Yii::getAlias('@webroot').$model->thumb);
                $this->flash('success', Yii::t('easyii/news', 'News image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, News::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, News::STATUS_OFF);
    }
}