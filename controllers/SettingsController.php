<?php
namespace yii\easyii\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\easyii\models\Setting;

class SettingsController extends \yii\easyii\components\Controller
{
    public $rootActions = ['create', 'delete'];

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Setting::find()->where(['>=', 'visibility', IS_ROOT ? Setting::VISIBLE_ROOT : Setting::VISIBLE_ALL]),
        ]);
        Yii::$app->user->setReturnUrl('/admin/settings');

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Setting;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii', 'Setting created'));
                    return $this->redirect('/admin/settings');
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
        $model = Setting::findOne($id);

        if($model === null || ($model->visibility < (IS_ROOT ? Setting::VISIBLE_ROOT : Setting::VISIBLE_ALL))){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/settings']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii', 'Setting updated'));
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
        if(($model = Setting::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii', 'Setting deleted'));
    }
}