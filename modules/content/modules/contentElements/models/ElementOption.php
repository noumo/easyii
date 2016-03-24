<?php
namespace yii\easyii\modules\content\modules\contentElements\models;

use Yii;
use yii\db\Expression;
use yii\easyii\components\ActiveRecord;

/**
 * Class ElementOption
 *
 * @property string  $type
 * @property string  $name
 * @property string  $value
 * @property integer $element_id
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class ElementOption extends ActiveRecord
{
	const TYPE_HTML_CLASS = 'html-class';
	const TYPE_HTML_STYLE = 'html-style';

	public $scenario = 'insert';

	public static function tableName()
	{
		return 'easyii_content_element_option';
	}

	public static function create($type, $value = null)
	{
		$option = new self();
		$option->type = $type;
		$option->value = $value;

		return $option;
	}

	public static function getTypes()
	{
		return [
			self::TYPE_HTML_CLASS => Yii::t('easyii/content', 'Html Class'),
			self::TYPE_HTML_STYLE => Yii::t('easyii/content', 'Html Style'),
		];
	}

	public function getTypeLabel()
	{
		$types = self::getTypes();

		return $types[$this->type];
	}

	public function rules()
	{
		return [
			[['type'], 'required'],
			['type', 'string', 'max' => 50],
			['type', 'in', 'range' => array_keys(self::getTypes())],
			['value', 'string'],
			['value', 'default', 'value' => null],
			[['element_id'], 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'type' => $this->isNewRecord ? Yii::t('easyii/content', 'Type') : $this->getTypeLabel(),
			'value' => Yii::t('easyii/content', 'Value'),
		];
	}

	public function behaviors()
	{
		return [
			[
				'class' => \yii\behaviors\TimestampBehavior::className(),
				'createdAtAttribute' => 'timestamp',
				'updatedAtAttribute' => 'timestamp',
				'value' => new Expression('CURRENT_TIMESTAMP')
			],
		];
	}

	public function formName()
	{
		if ($this->isNewRecord) {
			// Todo: Not so really unique!
			$unique = spl_object_hash($this);
		}
		else {
			$unique = $this->primaryKey;
		}

		$formName = parent::formName();

		return $formName . "[$unique]";
	}

	public function getElement()
	{
		return $this->hasOne(BaseElement::className(), ['element_id' => 'element_id']);
	}

	public function afterSave($insert, $attributes)
	{
		$this->scenario = 'update';

		parent::afterSave($insert, $attributes);
	}

	public function afterFind()
	{
		$this->scenario = 'update';

		parent::afterFind();
	}
}