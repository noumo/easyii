<?php
namespace yii\easyii\modules\file\models;

use Yii;
use yii\easyii\behaviors\SortableModel;

class File extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_files';
    }

    public function rules()
    {
        return [
            ['file', 'file'],
            ['title', 'required'],
            [['title', 'slug'], 'trim'],
            ['slug',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['slug', 'unique'],
            ['slug', 'default', 'value' => null],
            [['downloads', 'size'], 'number', 'integerOnly' => true],
            ['time', 'default', 'value' => time()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'file' => Yii::t('easyii', 'File'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->isNewRecord && $this->file !== $this->oldAttributes['file']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['file']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->file);
    }
}