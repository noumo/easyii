<?php
namespace yii\easyii\models;

use yii\base\Model;

class CopyModuleForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'match', 'pattern' => '/^[\w]+$/'],
            ['name', 'unique', 'targetClass' => Module::className()],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => 'New module name',
        ];
    }
}