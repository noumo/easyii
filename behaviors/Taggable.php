<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\easyii\models\Tag;
use yii\easyii\models\TagAssign;

class Taggable extends \yii\base\Behavior
{
    public $tagValues;
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

    public function canGetProperty($name, $checkVars = true)
    {
        if ($name === 'tagNames') {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    public function __get($name)
    {
        return $name == 'tagNames' ? $this->getTagNames() : $this->getTagsArray();
    }

    public function canSetProperty($name, $checkVars = true)
    {
        if ($name === 'tagNames') {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    public function __set($name, $value)
    {
        $this->tagValues = $value;
    }

    private function getTagNames()
    {
        return implode(', ', $this->getTagsArray());
    }

    public function getTagsArray()
    {
        if($this->_tags === null){
            $this->_tags = [];
            foreach($this->getTags()->all() as $tag) {
                $this->_tags[] = $tag->name;
            }
        }
        return $this->_tags;
    }

    public function afterSave()
    {
        $names = $this->filterTagValues($this->tagValues);

        if ($this->tagValues === null) {
            $this->tagValues = $this->owner->tagNames;
        }
        if (!$this->owner->isNewRecord) {
            $this->beforeDelete();
        }

        $tagAssigns = [];
        $modelClass = get_class($this->owner);
        foreach ($names as $name) {
            if(!($tag = Tag::findOne(['name' => $name]))) {
                $tag = new Tag(['name' => $name]);
            }
            $tag->frequency++;
            if ($tag->save()) {
                $updatedTags[] = $tag;
                $tagAssigns[] = [$modelClass, $this->owner->primaryKey, $tag->tag_id];
            }
        }

        Yii::$app->db->createCommand()->batchInsert(TagAssign::tableName(), ['class', 'item_id', 'tag_id'], $tagAssigns)->execute();

        $this->owner->populateRelation('tags', $updatedTags);
    }

    public function beforeDelete()
    {
        $pks = [];

        foreach($this->getTags()->all() as $tag){
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