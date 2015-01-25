<?php
namespace yii\easyii\validators;

use yii\validators\Validator;

class EscapeValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = filter_var($model->$attribute, FILTER_SANITIZE_STRING);
    }
}