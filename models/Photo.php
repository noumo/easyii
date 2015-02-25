<?php
namespace yii\easyii\models;

use Yii;
use yii\easyii\behaviors\SortableModel;

class Photo extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_photos';
    }

    public function rules()
    {
        return [
            [['model', 'item_id'], 'required'],
            ['item_id', 'integer'],
            ['image', 'image'],
            ['description', 'trim']
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className()
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
        @unlink(Yii::getAlias('@webroot').$this->thumb);
    }
}