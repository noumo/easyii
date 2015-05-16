<?php
namespace yii\easyii\modules\file\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
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
            ['title', 'string', 'max' => 128],
            ['title', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            [['downloads', 'size'], 'integer'],
            ['time', 'default', 'value' => time()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'file' => Yii::t('easyii', 'File'),
            'slug' => Yii::t('easyii', 'Slug')
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'seoBehavior' => SeoBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->file !== $this->oldAttributes['file']){
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