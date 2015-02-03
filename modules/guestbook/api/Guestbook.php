<?php
namespace yii\easyii\modules\guestbook\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;

use yii\easyii\modules\guestbook\models\Guestbook as GuestbookModel;
use yii\easyii\widgets\ReCaptcha;


class Guestbook extends \yii\easyii\components\API
{
    private $_adp;
    private $_last;
    private $_options = [
        'pageSize' => 20,
        'where' => '',
    ];
    
    public function api_form()
    {
        $model = new GuestbookModel;
        $settings = Yii::$app->getModule('admin')->activeModules['guestbook']->settings;

        ob_start();
        $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'action' => '/admin/guestbook/send'
        ]);

        switch(Yii::$app->session->getFlash(GuestbookModel::FLASH_KEY)){
            case 'success' :
                $message = Yii::$app->getModule('admin')->activeModules['guestbook']->settings['preModerate'] ?
                    Yii::t('easyii/guestbook/api', 'Message successfully sent and will be published after moderation') :
                    Yii::t('easyii/guestbook/api', 'Message successfully added');

                echo Alert::widget(['options' => ['class' => 'alert-success'],'body' => $message]);
                break;
            case 'error' :
                echo Alert::widget(['options' => ['class' => 'alert-danger'],'body' => Yii::t('easyii/guestbook/api', 'An error has occurred')]);
                break;
        }
        echo $form->field($model, 'name');
        if($settings['enableTitle']) echo $form->field($model, 'title');

        echo $form->field($model, 'text')->textarea();

        if($settings['enableCaptcha']) echo $form->field($model, 'reCaptcha')->widget(ReCaptcha::className());

        echo Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_all($options = [])
    {
        $result = [];

        if(is_array($options) && count($options)) {
            $this->_options = array_merge($this->_options, $options);
        }

        foreach($this->adp->models as $guestbook){
            $result[] = $this->parseGuestbook($guestbook);
        }
        return $result;        
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];
        foreach(GuestbookModel::find()->status(GuestbookModel::STATUS_ON)->desc()->limit($limit)->all() as $guestbook){
            $result[] = $this->parseGuestbook($guestbook);
        }

        if($limit > 1){
            return $result;
        }else{
            $this->_last = count($result) ? $result[0] : $this->createObject('');
            return $this->_last;
        }
    }

    public function api_pagination()
    {
        return $this->adp->pagination;
    }

    public function api_pages()
    {
        return LinkPager::widget(['pagination' => $this->adp->pagination]);
    }

    public function api_create($data)
    {
        $model = new GuestbookModel($data);
        if($model->save()){
            return ['result' => 'success'];
        } else{
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }

    protected function getAdp()
    {
        if(!$this->_adp){
            $query = GuestbookModel::find()->status(GuestbookModel::STATUS_ON)->desc();

            if($this->_options['where']){
                $query->andWhere($this->_options['where']);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $this->_options['pageSize']
                ]
            ]);
        }
        return $this->_adp;
    }

    private function parseGuestbook($guestbook)
    {
        if(LIVE_EDIT){
            if($guestbook->title) $guestbook->title = $this->wrapLiveEdit($guestbook->title, 'a/view/'.$guestbook->primaryKey);
            if($guestbook->answer) $guestbook->answer = $this->wrapLiveEdit($guestbook->answer, 'a/view/'.$guestbook->primaryKey, 'div');
            $guestbook->text = $this->wrapLiveEdit($guestbook->text, 'a/view/'.$guestbook->primaryKey, 'div');
        }
        return $this->createObject($guestbook->attributes);
    }

    private function createObject($data)
    {
        $is_string = !is_array($data);

        return (object)[
            'id' => $is_string ? '' : $data['guestbook_id'],
            'name' => $is_string ? $data : $data['name'],
            'title' => $is_string ? $data : $data['title'],
            'text' => $is_string ? $data : nl2br($data['text']),
            'answer' => $is_string ? $data : nl2br($data['answer']),
            'time' => $is_string ? $data : $data['time'],
            'date' => $is_string ? '' : Yii::$app->formatter->asDatetime($data['time'], 'medium'),
        ];
    }
}