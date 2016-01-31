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

    public function sort($order = SORT_DESC)
    {
        $this->orderBy(['order_num' => $order, 'lft' => SORT_ASC]);
        return $this;
    }
}