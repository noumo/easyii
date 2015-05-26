<?php
namespace yii\easyii\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Sortable behavior. Enables model to be sorted manually by admin
 * @package yii\easyii\behaviors
 */
class SortableModel extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'findMaxOrderNum',
        ];
    }

    public function findMaxOrderNum()
    {
        if(!$this->owner->order_num) {
            $maxOrderNum = (int)(new Query())
                ->select('MAX(`order_num`)')
                ->from($this->owner->tableName())
                ->scalar();
            $this->owner->order_num = ++$maxOrderNum;
        }
    }
}