<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\easyii\models\Tag;
use yii\easyii\models\TagAssign;

class Taggable extends \yii\base\Behavior
{
    private $_tags;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function getTagAssigns()
    {
        return $this->owner->hasMany(TagAssign::className(), ['item_id' => $this->owner->primaryKey()[0]])->where(['class' => get_class($this->owner)]);
    }

    public function getTags()
    {
        return $this->owner->hasMany(Tag::className(), ['tag_id' => 'tag_id'])->via('tagAssigns');
    }

    public function getTagNames()
    {
        return implode(', ', $this->getTagsArray());
    }

    public function setTagNames($values)
    {
        $this->_tags = $this->filterTagValues($values);
    }

    public function getTagsArray()
    {
        if($this->_tags === null){
            $this->_tags = [];
            foreach($this->owner->tags as $tag) {
                $this->_tags[] = $tag->name;
            }
        }
        return $this->_tags;
    }

    public function afterSave()
    {
        if(!$this->owner->isNewRecord) {
            $this->beforeDelete();
        }

        if(count($this->_tags)) {
            $tagAssigns = [];
            $modelClass = get_class($this->owner);

            foreach ($this->_tags as $name) {
                if (!($tag = Tag::findOne(['name' => $name]))) {
                    $tag = new Tag(['name' => $name]);
                }
                $tag->frequency++;
                if ($tag->save()) {
                    $updatedTags[] = $tag;
                    $tagAssigns[] = [$modelClass, $this->owner->primaryKey, $tag->tag_id];
                }
            }

            if(count($tagAssigns)) {
                Yii::$app->db->createCommand()->batchInsert(TagAssign::tableName(), ['class', 'item_id', 'tag_id'], $tagAssigns)->execute();
                $this->owner->populateRelation('tags', $updatedTags);
            }
        }
    }

    public function beforeDelete()
    {
        $pks = [];

        foreach($this->owner->tags as $tag){
            $pks[] = $tag->primaryKey;
        }

        if (count($pks)) {
            Tag::updateAllCounters(['frequency' => -1], ['in', 'tag_id', $pks]);
        }
        Tag::deleteAll(['frequency' => 0]);
        TagAssign::deleteAll(['class' => get_class($this->owner), 'item_id' => $this->owner->primaryKey]);
    }

    /**
     * Filters tags.
     * @param string|string[] $values
     * @return string[]
     */
    public function filterTagValues($values)
    {
        return array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace('/\s+/u', ' ', is_array($values) ? implode(',', $values) : $values),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}