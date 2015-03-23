<?php
namespace yii\easyii\components;

class NSActiveRecord extends ActiveRecord
{
    public static function find()
    {
        return new NSActiveQuery(get_called_class());
    }
}