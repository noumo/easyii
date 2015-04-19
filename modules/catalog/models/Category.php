<?php
namespace yii\easyii\modules\catalog\models;

class Category extends \yii\easyii\components\CategoryModel
{
    static $fieldTypes = [
        'string' => 'String',
        'text' => 'Text',
        'boolean' => 'Boolean',
        'select' => 'Select',
        'checkbox' => 'Checkbox'
    ];

    public static function tableName()
    {
        return 'easyii_catalog_categories';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->fields || !is_array($this->fields)){
                $this->fields = [];
            }
            $this->fields = json_encode($this->fields);

            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->fields = $this->fields !== '' ? json_decode($this->fields) : [];
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getItems()->all() as $item){
            $item->delete();
        }
    }
}