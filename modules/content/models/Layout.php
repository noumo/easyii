<?php
namespace yii\easyii\modules\content\models;

use yii\db\ActiveQuery;

class Layout extends \yii\easyii\components\CategoryModel
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
		return 'easyii_content_layouts';
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($insert && ($parent = $this->parents(1)->one())) {
				$this->fields = $parent->fields;
			}

			if (!$this->fields || !is_array($this->fields)) {
				$this->fields = [];
			}
			$this->fields = json_encode($this->fields);

			return true;
		}
		else {
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

	/**
	 * @return ActiveQuery
	 */
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

	private function parseFields()
	{
		$this->fields = $this->fields !== '' ? json_decode($this->fields) : [];
	}
}