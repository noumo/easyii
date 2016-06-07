<?php
namespace yii\easyii\modules\entity\models;

class Category extends \yii\easyii\components\CategoryModel
{
    static $fieldTypes = [
        'string' => 'String',
        'text' => 'Text',
        'html' => 'Html',
        'boolean' => 'Boolean',
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'file' => 'File',
        'date' => 'Date'
    ];

    public static function tableName()
    {
        return 'easyii_entity_categories';
    }

    public function rules()
    {
        return array_merge([
            ['cache', 'integer'],
            ['fields', 'safe'],
        ], parent::rules());
    }

    public function attributeLabels()
    {
        return array_merge(['cache' => \Yii::t('easyii', 'Cache')], parent::attributeLabels());
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert && ($parent = $this->parents(1)->one())){
                $this->fields = $parent->fields;
            }

            if(!$this->fields || !is_array($this->fields)){
                $this->fields = [];
            }
            $this->fields = json_encode($this->fields);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $attributes)
    {
        parent::afterSave($insert, $attributes);
        $this->parseFields();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->parseFields();
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

    public function getFieldByName($name)
    {
        foreach($this->fields as $field){
            if($field->name == $name){
                return $field;
            }
        }
        return null;
    }

    private function parseFields()
    {
        $this->fields = $this->fields !== '' ? json_decode($this->fields) : [];
    }

    public static function getCacheName($category_id)
    {
        return 'entity' . $category_id;
    }
}