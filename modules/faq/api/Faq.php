<?php
namespace yii\easyii\modules\faq\api;

use Yii;
use yii\easyii\helpers\Data;
use yii\easyii\modules\faq\models\Faq as FaqModel;

class Faq extends \yii\easyii\components\API
{
    public function api_all()
    {
        $data = Data::cache(FaqModel::CACHE_KEY, 3600, function(){
            return FaqModel::find()->select(['question', 'answer'])->status(FaqModel::STATUS_ON)->sort()->asArray()->all();
        });

        $result = [];
        foreach($data as $entry){
            $result[] = $this->parseEntry($entry);
        }

        return $result;
    }

    private function parseEntry($entry)
    {
        if(LIVE_EDIT){
            $entry['question'] = $this->wrapLiveEdit($entry['question'], 'a/edit/'.$entry['faq_id'], 'div');
            $entry['answer'] = $this->wrapLiveEdit($entry['answer'], 'a/edit/'.$entry['faq_id'], 'div');
        }
        return (object)$entry;
    }
}