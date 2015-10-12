<?php
namespace yii\easyii\modules\faq\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\CommonActions;
use yii\widgets\ActiveForm;
use yii\easyii\components\Controller;
use yii\easyii\modules\faq\models\Faq;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => CommonActions::className(),
                'model' => Faq::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Faq::find()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new Faq;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii/faq', 'Entry created'));
                    return $this->redirect(['/admin/'.$this->module->id]);
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
        $model = Faq::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii/faq', 'Entry updated'));
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
        return $this->deleteModel($id, Yii::t('easyii/faq', 'Entry deleted'));
    }

    public function actionUp($id)
    {
        return $this->moveByNum($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->moveByNum($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Faq::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Faq::STATUS_OFF);
    }
}