<?php
namespace yii\easyii\components;

/**
 * Base active query class for models
 * @package yii\easyii\components
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * Apply condition by status
     * @param $status
     * @return $this
     */
    public function status($status)
    {
        $this->andWhere(['status' => (int)$status]);
        return $this;
    }

    /**
     * Order by primary key DESC
     * @return $this
     */
    public function desc()
    {
        $model = $this->modelClass;
        $this->orderBy([$model::primaryKey()[0] => SORT_DESC]);
        return $this;
    }

    /**
     * Order by primary key ASC
     * @return $this
     */
    public function asc()
    {
        $model = $this->modelClass;
        $this->orderBy([$model::primaryKey()[0] => SORT_ASC]);
        return $this;
    }

    /**
     * Order by order_num
     * @param $order
     * @return $this
     */
    public function sort($order = SORT_DESC)
    {
        $this->orderBy(['order_num' => $order]);
        return $this;
    }

    /**
     * Order by date
     * @param $order
     * @return $this
     */
    public function sortDate($order = SORT_DESC)
    {
        $this->orderBy(['time' => $order]);
        return $this;
    }
}