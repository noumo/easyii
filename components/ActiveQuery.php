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
     * @return $this
     */
    public function sort()
    {
        $this->orderBy(['order_num' => SORT_DESC]);
        return $this;
    }

    /**
     * Order by date
     * @return $this
     */
    public function sortDate()
    {
        $this->orderBy(['time' => SORT_DESC]);
        return $this;
    }
}