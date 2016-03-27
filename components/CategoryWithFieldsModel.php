<?php
namespace yii\easyii\components;

/**
 * Extended CategoryModel with fields. Shared by categories
 * @package yii\easyii\components
 * @inheritdoc
 */
class CategoryWithFieldsModel extends CategoryModel
{
    public static $FIELD_TYPES = [
        'string' => 'String',
        'text' => 'Text',
        'html' => 'Html',
        'boolean' => 'Boolean',
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'file' => 'File',
        'date' => 'Date'
    ];
    
    public function rules()
    {
        return array_merge([
            ['fields', 'safe'],
        ], parent::rules());
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
}