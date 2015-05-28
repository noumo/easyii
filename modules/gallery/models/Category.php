<?php
namespace yii\easyii\modules\gallery\models;

use yii\easyii\components\CategoryModel;
use yii\easyii\models\Photo;

class Category extends CategoryModel
{
    public static function tableName()
    {
        return 'easyii_gallery_categories';
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'category_id'])->where(['class' => self::className()])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}