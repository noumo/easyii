<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;

/**
 * CacheFlush behavior
 * @package yii\easyii\behaviors
 * @inheritdoc
 */
class CacheFlush extends \yii\base\Behavior
{
    /** @var  string */
    public $key;

    public function attach($owner)
    {
        parent::attach($owner);

        if(!$this->key) $this->key = constant(get_class($owner).'::CACHE_KEY');
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'flush',
            ActiveRecord::EVENT_AFTER_UPDATE => 'flush',
            ActiveRecord::EVENT_AFTER_DELETE => 'flush',
        ];
    }

    /**
     * Flush cache
     */
    public function flush()
    {
        if($this->key) {
            if(is_array($this->key)){
                foreach($this->key as $key){
                    Yii::$app->cache->delete($key);
                }
            } else {
                Yii::$app->cache->delete($this->key);
            }
        }
        else{
            Yii::$app->cache->flush();
        }
    }
}