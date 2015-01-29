<?php
namespace yii\easyii\behaviors;

use yii\db\ActiveRecord;

class SortableModel extends \yii\base\Behavior
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
            $maxOrderNum = (int)(new \yii\db\Query())
                ->select('MAX(`order_num`)')
                ->from($this->owner->tableName())
                ->scalar();
            $this->owner->order_num = ++$maxOrderNum;
        }
    }
}