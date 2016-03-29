<?php
namespace yii\easyii\modules\catalog\models;

use yii\easyii\components\CategoryWithFieldsModel;

class Category extends CategoryWithFieldsModel
{
    public static function tableName()
    {
        return 'easyii_catalog_categories';
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'id'])->sortDate();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getItems()->all() as $item){
            $item->delete();
        }
    }
}