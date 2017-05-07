<?php
namespace yii\easyii\components;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class ActiveQueryNS extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    public function sort()
    {
        $this->orderBy('order_num ASC, lft ASC');
        return $this;
    }
}