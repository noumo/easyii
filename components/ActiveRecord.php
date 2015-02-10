<?php
namespace yii\easyii\components;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public static $slugPattern = '/^[0-9a-z-]{0,128}$/';

    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    public function formatErrors()
    {
        $result = '';
        foreach($this->getErrors() as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
        }
        return $result;
    }
}