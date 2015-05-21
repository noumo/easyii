<?php
namespace yii\easyii\modules\text\api;

use Yii;
use yii\easyii\helpers\Data;
use yii\helpers\Url;
use yii\easyii\modules\text\models\Text as TextModel;

class Text extends \yii\easyii\components\API
{
    private $_texts = [];

    public function init()
    {
        parent::init();

        $texts = Data::cache(TextModel::CACHE_KEY, 3600, function(){
            return TextModel::find()->asArray()->all();
        });
        foreach($texts as $text) {
            $this->_texts[$text['text_id']] = $this->parseText($text);
        }
    }

    public function api_get($id_slug)
    {
        return $this->findText($id_slug);
    }

    private function findText($id_slug)
    {
        $text = null;
        if(isset($this->_texts[$id_slug])){
            $text = $this->_texts[$id_slug];
        }
        else {
            foreach ($this->_texts as $item) {
                if($item->slug == $id_slug){
                    $text = $item;
                    break;
                }
            }
        }
        return ($text !== null) ? nl2br($text->text) : $this->notFound($id_slug);
    }

    private function parseText($text)
    {
        return (object)[
            'text' => LIVE_EDIT ? $this->wrapLiveEdit($text['text'], 'a/edit/'.$text['text_id']) : $text['text'],
            'slug' => $text['slug']
        ];
    }

    private function notFound($id_slug)
    {
        if(Yii::$app->user->isGuest) {
            return '';
        }

        elseif(preg_match(TextModel::$SLUG_PATTERN, $id_slug)){

            return '<a href="' . Url::to(['/admin/text/a/create', 'slug' => $id_slug]) . '" target="_blank">'.Yii::t('easyii/text/api', 'Create text').'</a>';
        }
        else{
            return $this->errorText('WRONG TEXT IDENTIFIER');
        }
    }
}
