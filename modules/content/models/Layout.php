<?php
namespace yii\easyii\modules\content\models;

use yii;
use yii\db\ActiveQuery;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\ActiveRecord;

class Layout extends ActiveRecord
{
	use yii\easyii\components\TreeTrait;
	use yii\easyii\components\FlatTrait;

	const STATUS_OFF = 0;
	const STATUS_ON = 1;

	static $fieldTypes = [
		'string' => 'String',
		'text' => 'Text',
		'html' => 'Html',
		'boolean' => 'Boolean',
		'select' => 'Select',
		'checkbox' => 'Checkbox'
	];

	public static function tableName()
	{
		return 'easyii_content_layouts';
	}

	public function rules()
	{
		return [
			['title', 'required'],
			['title', 'trim'],
			[['title', 'slug'], 'string', 'max' => 128],
			['image_file', 'image'],
			['slug', 'match', 'pattern' => static::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
			['slug', 'default', 'value' => null],
			[['status'], 'integer'],
			['status', 'default', 'value' => self::STATUS_ON]
		];
	}

	public function attributeLabels()
	{
		return [
			'title' => Yii::t('easyii', 'Title'),
			'image_file' => Yii::t('easyii', 'Image'),
			'slug' => Yii::t('easyii', 'Slug'),
		];
	}

	public function behaviors()
	{
		return [
			'cacheflush' => [
				'class' => yii\easyii\behaviors\CacheFlush::className(),
				'key' => [static::tableName().'_tree', static::tableName().'_flat']
			],
			SortableModel::className(),
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
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
		return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sort();
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