<?php
namespace yii\easyii\modules\subscribe\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\actions\DeleteAction;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\components\Controller;
use yii\easyii\models\Setting;
use yii\easyii\modules\subscribe\models\Subscriber;
use yii\easyii\modules\subscribe\models\History;

class AController extends Controller
{
    public $modelClass = 'yii\easyii\modules\subscribe\models\History';

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => Subscriber::className(),
                'successMessage' => Yii::t('easyii/subscribe', 'Subscriber deleted')
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Subscriber::find()->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionHistory()
    {
        $this->setReturnUrl();

        $data = new ActiveDataProvider([
            'query' => History::find()->desc(),
        ]);
        return $this->render('history', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new History;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else
            {
                if($model->validate() && $this->send($model)){
                    $this->flash('success', Yii::t('easyii/subscribe', 'Subscribe successfully created and sent'));
                    return $this->redirect(['/admin/'.$this->module->id.'/a/history']);
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

    private function send($model)
    {
        $text = $model->body.
                "<br><br>".
                "--------------------------------------------------------------------------------";

        foreach(Subscriber::find()->all() as $subscriber){
			$unsubscribeLink = '<br><a href="' . Url::to(['/admin/'.$this->module->id.'/send/unsubscribe', 'email' => $subscriber->email], true) . '" target="_blank">'.Yii::t('easyii/subscribe', 'Unsubscribe').'</a>';
            if(Yii::$app->mailer->compose()
                ->setFrom(Setting::get('robot_email'))
                ->setTo($subscriber->email)
                ->setSubject($model->subject)
                ->setHtmlBody($text.$unsubscribeLink)
                ->setReplyTo(Setting::get('admin_email'))
                ->send())
            {
                $model->sent++;
            }
        }

        return $model->save();
    }
}
