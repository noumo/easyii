<?php
namespace yii\easyii\behaviors;

use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\easyii\components\ActiveRecord;

class SlugBehavior extends SluggableBehavior
{
    public $attributes = [BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'slug'];
    public $attribute = 'title';
    public $ensureUnique = true;

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if($this->isEnteredManually()) {
            $slug = $this->owner->{$this->slugAttribute};
            if(!$this->validateSlug($slug)) {
                $this->owner->addError($this->slugAttribute, \Yii::t('yii', '{attribute} "{value}" has already been taken.', ['attribute' => \Yii::t('easyii', 'Slug'), 'value' => $slug]));
                return null;
            }
            return $slug;
        } else {
            return parent::getValue($event);
        }
    }

    protected function isEnteredManually()
    {
        if($this->owner->isNewRecord && !empty($this->owner->{$this->slugAttribute})) {
            return true;
        }
        if(!$this->owner->isNewRecord && $this->owner->isAttributeChanged($this->slugAttribute) && !$this->immutable) {
            return true;
        }
        return false;
    }
}