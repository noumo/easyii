<?php
namespace yii\easyii\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\models\Module;
use yii\easyii\behaviors\SortableController;
use yii\easyii\behaviors\StatusController;

class ModulesController extends \yii\easyii\components\Controller
{
    public $rootActions = 'all';

    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Module::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Module::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Module::find()->sort(),
        ]);
        Yii::$app->user->setReturnUrl('/admin/modules');

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Module;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii', 'Module created'));
                    return $this->redirect(['/admin/modules']);
                }
                else{
                    $this->flash('error', Yii::t('Create error. {0}', $model->formatErrors()));
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
        $model = Module::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/modules']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii', 'Module updated'));
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

    public function actionSettings($id)
    {
        $model = Module::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/modules']);
        }

        if (Yii::$app->request->post('Settings')) {
            $model->setSettings(Yii::$app->request->post('Settings'));
            if($model->save()){
                $this->flash('success', Yii::t('easyii', 'Module settings updated'));
            }
            else{
                $this->flash('error', Yii::t('easyii', Yii::t('easyii', 'Update error. {0}', $model->formatErrors())));
            }
            return $this->refresh();
        }
        else {

            return $this->render('settings', [
                'model' => $model
            ]);
        }
    }

    public function actionRestoresettings($id)
    {
        $model = Module::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        else{
            $model->settings = '';
            $model->save();
            $this->flash('success', Yii::t('easyii', 'Module default settings was restored'));
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Module::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii', 'Module deleted'));
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
        return $this->changeStatus($id, Module::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Module::STATUS_OFF);
    }
}