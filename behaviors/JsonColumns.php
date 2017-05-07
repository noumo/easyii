<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;

/**
 * JsonColumns behavior
 * @package yii\easyii\behaviors
 * @inheritdoc
 */
class JsonColumns extends \yii\base\Behavior
{
    /** @var array */
    public $columns = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'encodeJson',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encodeJson',
            ActiveRecord::EVENT_AFTER_INSERT => 'decodeJson',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decodeJson',
            ActiveRecord::EVENT_AFTER_FIND => 'decodeJson',
        ];
    }

    public function encodeJson()
    {
        foreach($this->columns as $attribute) {
            if(array_key_exists($attribute, $this->owner->attributes)) {
                $data = $this->owner->{$attribute};
                if (!$data || (!is_array($data) && !is_object($data))) {
                    $data = new \stdClass();
                }
                $this->owner->{$attribute} = json_encode($data);
            }
        }
    }

    public function decodeJson()
    {
        foreach($this->columns as $attribute) {
            if(array_key_exists($attribute, $this->owner->attributes)) {
                $data = $this->owner->{$attribute};
                if($data && is_string($data)) {
                    $data = json_decode($data);
                }
                $this->owner->{$attribute} = ($data && (is_array($data) || is_object($data))) ? $data : new \stdClass();
            }
        }
    }
}