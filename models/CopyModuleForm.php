<?php
namespace yii\easyii\models;

use Yii;
use yii\base\Model;

class CopyModuleForm extends Model
{
    public $title;
    public $name;

    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            ['name', 'match', 'pattern' => '/^[\w]+$/'],
            ['name', 'unique', 'targetClass' => Module::className()],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => 'New module name',
            'title' => Yii::t('easyii', 'Title'),
        ];
    }
}