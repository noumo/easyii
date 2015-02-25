<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\easyii\models\SeoText;

class SeoBehavior extends \yii\base\Behavior
{
    private $_model;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterInsert()
    {
        if($this->seoText->load(Yii::$app->request->post())){
            if(!$this->seoText->isEmpty()){
                $this->seoText->save();
            }
        }
    }

    public function afterUpdate()
    {
        if($this->seoText->load(Yii::$app->request->post())){
            if(!$this->seoText->isEmpty()){
                $this->seoText->save();
            } else {
                if($this->seoText->primaryKey){
                    $this->seoText->delete();
                }
            }
        }
    }

    public function afterDelete()
    {
        if(!$this->seoText->isNewRecord){
            $this->seoText->delete();
        }
    }

    public function getSeo_h1()
    {
        return $this->seoText->h1;
    }

    public function getSeo_title()
    {
        return $this->seoText->title;
    }

    public function getSeo_keywords()
    {
        return $this->seoText->keywords;
    }

    public function getSeo_description()
    {
        return $this->seoText->description;
    }

    public function getSeoText()
    {
        if(!$this->_model)
        {
            if($this->owner && $this->owner->primaryKey) {
                $itemModel = get_class($this->owner);
                $this->_model = SeoText::findOne(['model' => $itemModel, 'item_id' => $this->owner->primaryKey]);
                if(!$this->_model){
                    $this->_model = new SeoText([
                        'model' => $itemModel,
                        'item_id' => $this->owner->primaryKey
                    ]);
                }
            } else {
                $this->_model = new SeoText();
            }
        }

        return $this->_model;
    }
}