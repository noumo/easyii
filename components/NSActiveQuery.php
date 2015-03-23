<?php
namespace yii\easyii\components;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class NSActiveQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    public function sort()
    {
        $this->orderBy('order_num DESC, lft ASC');
        return $this;
    }
}