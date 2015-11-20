<?php
namespace yii\easyii\modules\file\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByNumAction;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\easyii\components\Controller;
use yii\easyii\modules\file\models\File;
use yii\easyii\helpers\Upload;

class AController extends Controller
{
    public $modelClass = 'yii\easyii\modules\file\models\File';

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'successMessage' => Yii::t('easyii/file', 'File deleted')
            ],
            'up' => SortByNumAction::className(),
            'down' => SortByNumAction::className(),
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => File::find()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new File;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'file')))
                {
                    $model->file = $fileInstanse;
                    if($model->validate(['file'])){
                        $model->file = Upload::file($fileInstanse, 'file', false);
                        $model->size = $fileInstanse->size;

                        if($model->save()){
                            $this->flash('success', Yii::t('easyii/file', 'File created'));
                            return $this->redirect(['/admin/'.$this->module->id]);
                        }
                        else{
                            $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                        }
                    }
                    else {
                        $this->flash('error', Yii::t('easyii/file', 'File error. {0}', $model->formatErrors()));
                    }
                }
                else {
                    $this->flash('error', Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('file')]));
                }
                return $this->refresh();
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'file')))
                {
                    $model->file = $fileInstanse;
                    if($model->validate(['file'])){
                        $model->file = Upload::file($fileInstanse, 'file', false);
                        $model->size = $fileInstanse->size;
                        $model->time = time();
                    }
                    else {
                        $this->flash('error', Yii::t('easyii/file', 'File error. {0}', $model->formatErrors()));
                        return $this->refresh();
                    }
                }
                else{
                    $model->file = $model->oldAttributes['file'];
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/file', 'File updated'));
                }
                else {
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
}