<?php
namespace yii\easyii\modules\article\models;

use yii\easyii\components\CategoryModel;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class Category extends CategoryModel
{
    public static function tableName()
    {
        return 'easyii_article_categories';
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sortDate();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }
}