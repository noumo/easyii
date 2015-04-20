<?php
namespace yii\easyii\modules\feedback\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use yii\easyii\components\Controller;
use yii\easyii\models\Setting;
use yii\easyii\modules\feedback\models\Feedback;

class AController extends Controller
{
    public $new = 0;
    public $noAnswer = 0;

    public function init()
    {
        parent::init();

        $this->new = Yii::$app->getModule('admin')->activeModules['feedback']->notice;
        $this->noAnswer = Feedback::find()->status(Feedback::STATUS_VIEW)->count();
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Feedback::find()->status(Feedback::STATUS_NEW)->asc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionNoanswer()
    {
        $this->setReturnUrl();

        $data = new ActiveDataProvider([
            'query' => Feedback::find()->status(Feedback::STATUS_VIEW)->asc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionAll()
    {
        $this->setReturnUrl();

        $data = new ActiveDataProvider([
            'query' => Feedback::find()->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = Feedback::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if($model->status == Feedback::STATUS_NEW){
            $model->status = Feedback::STATUS_VIEW;
            $model->update();
        }

        if (Yii::$app->request->post('Feedback')) {
            if(filter_var(Setting::get('admin_email'), FILTER_VALIDATE_EMAIL))
            {
                $model->answer = trim(Yii::$app->request->post('Feedback')['answer']);
                if($this->sendAnswer($model)){
                    $this->flash('success', Yii::t('easyii/feedback', 'Answer successfully sent'));
                }
                else{
                    $this->flash('error', Yii::t('easyii/feedback/api', 'An error has occurred'));
                }
            }
            else {
                $this->flash('error', Yii::t('easyii/feedback', 'Please fill correct `Admin E-mail` in Settings'));
            }

            return $this->refresh();
        }
        else {
            if(!$model->answer) {
                if ($this->module->settings['answerHello']) $model->answer = Yii::t('easyii/feedback', $this->module->settings['answerHello']) . " " . $model->name . ".\n";
                if ($this->module->settings['answerFooter']) $model->answer .= "\n\n" . Yii::t('easyii/feedback', $this->module->settings['answerFooter']);
            }

            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    public function actionSetAnswer($id)
    {
        $model = Feedback::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        else{
            $model->status = Feedback::STATUS_ANSWER;
            if($model->update()) {
                $this->flash('success', Yii::t('easyii/feedback', 'Feedback updated'));
            }
            else{
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Feedback::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/feedback', 'Feedback deleted'));
    }

    public function sendAnswer($model)
    {
        $text = $model->answer;
        $text .= "\n\n\n";
        $text .= "-------------------------------------------------------------------------------- \n";
        $text .= Yii::$app->formatter->asDatetime($model->time, 'medium') . " you wrote: \n";

        foreach(explode("\n", $model->text) as $line){
            $text .= '> '.$line;
        }
        $sent = Yii::$app->mailer->compose()
            ->setFrom(Setting::get('robot_email'))
            ->setTo($model->email)
            ->setSubject(strtoupper(Yii::$app->request->serverName).' '.Yii::t('easyii/feedback', 'feedback answer'))
            ->setTextBody($text)
            ->setReplyTo(Setting::get('admin_email'))
            ->send();

        if($sent) {
            $model->status = Feedback::STATUS_ANSWER;
            $model->update();
            return true;
        }

        return false;
    }
}