<?php
namespace yii\easyii\modules\guestbook\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\components\Controller;
use yii\easyii\models\Module;
use yii\easyii\modules\guestbook\models\Guestbook;

class AController extends Controller
{
    public $modelClass = 'yii\easyii\modules\guestbook\models\Guestbook';
    public $new = 0;
    public $noAnswer = 0;

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'successMessage' => Yii::t('easyii/guestbook', 'Entry deleted')
            ],
            'on' => ChangeStatusAction::className(),
            'off' => ChangeStatusAction::className(),
        ];
    }

    public function init()
    {
        parent::init();

        $this->new = Yii::$app->getModule('admin')->activeModules['guestbook']->notice;
        $this->noAnswer = Guestbook::find()->where(['answer' => ''])->count();
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Guestbook::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionNoanswer()
    {
        $this->setReturnUrl();

        $data = new ActiveDataProvider([
            'query' => Guestbook::find()->where(['answer' => ''])->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->new > 0) {
            $model->new = 0;
            $model->update();
        }

        if (Yii::$app->request->post('Guestbook')) {
            $model->answer = trim(Yii::$app->request->post('Guestbook')['answer']);
            if ($model->save($model)) {
                if (Yii::$app->request->post('mailUser')) {
                    $model->notifyUser();
                }
                $this->flash('success', Yii::t('easyii/guestbook', 'Answer successfully saved'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
            return $this->refresh();
        } else {
            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    public function actionViewall()
    {
        Guestbook::updateAll(['new' => 0]);
        $module = Module::findOne(['name' => 'guestbook']);
        $module->notice = 0;
        $module->save();

        $this->flash('success', Yii::t('easyii/guestbook', 'Guestbook updated'));

        return $this->back();
    }

    public function actionSetnew($id)
    {
        $model = $this->findModel($id);

        $model->new = 1;
        if ($model->update()) {
            $this->flash('success', Yii::t('easyii/guestbook', 'Guestbook updated'));
        } else {
            $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
        }
        return $this->redirect($this->getReturnUrl(['/admin/' . $this->module->id]));
    }
}