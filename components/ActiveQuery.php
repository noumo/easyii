<?php
namespace yii\easyii\components;

class ActiveQuery extends \yii\db\ActiveQuery
{
    public function status($status)
    {
        $this->andWhere(['status' => (int)$status]);
        return $this;
    }

    public function desc()
    {
        $model = $this->modelClass;
        $this->orderBy($model::primaryKey()[0].' DESC');
        return $this;
    }

    public function asc()
    {
        $model = $this->modelClass;
        $this->orderBy($model::primaryKey()[0].' ASC');
        return $this;
    }

    public function sort()
    {
        $this->orderBy('order_num DESC');
        return $this;
    }

    public function sortDate()
    {
        $this->orderBy('time DESC');
        return $this;
    }
}