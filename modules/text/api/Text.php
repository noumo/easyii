<?php
namespace yii\easyii\modules\text\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\helpers\Data;
use yii\easyii\modules\text\models\Text as TextModel;
use yii\helpers\Html;
use yii\helpers\Url;

class Text extends API
{
    private $_texts = [];

    public function init()
    {
        parent::init();

        $this->_texts = Data::cache(TextModel::CACHE_KEY, 3600, function(){
            return TextModel::find()->asArray()->all();
        });
    }

    public function api_get($id_slug)
    {
        return $this->findText($id_slug);
    }

    private function findText($id_slug)
    {
        $text = null;
        foreach ($this->_texts as $item) {
            if($item['slug'] == $id_slug || $item['text_id'] == $id_slug){
                $text = nl2br($item['text']);
                break;
            }
        }

        if($text === null){
            return $this->notFound($id_slug);
        }

        return LIVE_EDIT ? API::liveEdit($text, $this->editLink) : $text;
    }

    private function notFound($id_slug)
    {
        $text = '';

        if(!Yii::$app->user->isGuest && preg_match(TextModel::$SLUG_PATTERN, $id_slug)){
            $text = Html::a(Yii::t('easyii/text/api', 'Create text'), ['/admin/text/a/create', 'slug' => $id_slug], ['target' => '_blank']);
        }

        return $text;
    }

    public function getEditLink(){
        return Url::to(['/admin/text/a/edit/', 'id' => $this->id]);
    }
}