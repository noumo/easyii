<?php
namespace yii\easyii\modules\guestbook\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
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
    private $_items;

    public function api_items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $query = GuestbookModel::find()->status(GuestbookModel::STATUS_ON)->orderBy('time DESC');

            if(!empty($options['where'])){
                $query->where($options['where']);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new GuestbookObject($model);
            }
        }
        return $this->_items;
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];
        foreach(GuestbookModel::find()->orderBy('time DESC')->limit($limit)->all() as $item){
            $result[] = new GuestbookObject($item);
        }

        if($limit > 1){
            return $result;
        } else {
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
    }
    
    public function api_form()
    {
        $model = new GuestbookModel;
        $settings = Yii::$app->getModule('admin')->activeModules['guestbook']->settings;

        ob_start();
        $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'action' => Url::to(['/admin/guestbook/send'])
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

    public function api_save($data)
    {
        $model = new GuestbookModel($data);
        if ($model->save()) {
            return ['result' => 'success'];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }
}