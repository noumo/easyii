<?php
namespace yii\easyii\components;

/**
 * Extended CategoryModel with fields. Shared by categories
 * @package yii\easyii\components
 * @inheritdoc
 */
class CategoryWithFieldsModel extends CategoryModel
{
    const FIELD_TYPE_STRING = 'string';
    const FIELD_TYPE_TEXT = 'text';
    const FIELD_TYPE_HTML = 'html';
    const FIELD_TYPE_BOOLEAN = 'boolean';
    const FIELD_TYPE_SELECT = 'select';
    const FIELD_TYPE_CHECKBOX = 'checkbox';
    const FIELD_TYPE_FILE = 'file';
    const FIELD_TYPE_DATE = 'date';
    const FIELD_TYPE_ADDRESS = 'address';
    
    public static $FIELD_TYPES = [
        self::FIELD_TYPE_STRING => 'String',
        self::FIELD_TYPE_TEXT => 'Text',
        self::FIELD_TYPE_HTML => 'Html',
        self::FIELD_TYPE_BOOLEAN => 'Boolean',
        self::FIELD_TYPE_SELECT => 'Select',
        self::FIELD_TYPE_CHECKBOX => 'Checkbox',
        self::FIELD_TYPE_FILE => 'File',
        self::FIELD_TYPE_DATE => 'Date',
        self::FIELD_TYPE_ADDRESS => 'Address'
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