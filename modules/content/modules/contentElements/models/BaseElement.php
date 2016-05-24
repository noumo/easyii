<?php
namespace yii\easyii\modules\content\modules\contentElements\models;

use yii;
use yii\easyii\components\ActiveRecord;
use yii\easyii\modules\content\models\Item;
use yii\helpers\Json;
use yii\easyii\modules\content\modules\contentElements\ContentElementModule;

/**
 * Class ContentElement
 *
 * @property integer $element_id
 * @property integer $parent_element_id
 * @property string $type
 * @property array $data
 * @property integer $order_num
 * @property integer $status
 *
 * @property BaseElement[] $elements
 * @property ElementOption[] $options
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
abstract class BaseElement extends ActiveRecord
{
	const STATUS_OFF = 0;
	const STATUS_ON = 1;

	public $scenario = 'insert';

	public static function tableName()
	{
		return 'easyii_content_element';
	}

	public static function instantiate($row)
	{
		$type = $row['type'];

		return ContentElementModule::create($type);
	}

	public static function elementId()
	{
		return ContentElementModule::getElementId(static::className());
	}

	public function init()
	{
		parent::init();

		$this->type = static::elementId();
	}

	public function render(yii\web\View $view)
	{
		$widget = ContentElementModule::createWidget($this);

		return $widget->runTemplate();
	}

	public function renderAsRoot(yii\web\View $view)
	{
		$widget = ContentElementModule::createWidget($this);

		return $widget->run('template');
	}

	public function rules()
	{
		return [
			[['!type'], 'string'],
			[['!order_num', '!parent_element_id'], 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [];
	}

	public function behaviors()
	{
		return [
			[
				'class' => yii\behaviors\TimestampBehavior::className(),
				'createdAtAttribute' => 'time',
				'updatedAtAttribute' => null,
			],
			yii\easyii\behaviors\SortableModel::className(),
		];
	}

	/**
	 * Sets the attribute values in a massive way.
	 * @param array $values attribute values (name => value) to be assigned to the model.
	 * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
	 * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
	 * @see safeAttributes()
	 * @see attributes()
	 */
	public function setActiveAttributes($values)
	{
		if (is_array($values)) {
			$attributes = array_flip($this->activeAttributes());
			foreach ($values as $name => $value) {
				if (isset($attributes[$name])) {
					$this->$name = $value;
				}
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function load($data, $formName = null)
	{
		$scope = $formName === null ? $this->formName() : $formName;
		if ($scope === '' && !empty($data)) {
			$this->setActiveAttributes($data);

			return true;
		} elseif (isset($data[$scope])) {
			$this->setActiveAttributes($data[$scope]);

			return true;
		} else {
			return false;
		}
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

		return "Element[$unique]";
	}

	public function getItem()
	{
		return $this->hasOne(Item::className(), ['item_id' => 'item_id']);
	}

	public function getElements()
	{
		return $this->hasMany(BaseElement::className(), ['parent_element_id' => 'element_id'])->orderBy('order_num');
	}

	/**
	 * @return yii\easyii\components\ActiveQuery
	 */
	public function getOptions()
	{
		return $this->hasMany(ElementOption::className(), ['element_id' => 'element_id'])->indexBy('type');
	}

	public function defaultOptions()
	{
		$options = [
			ElementOption::create(ElementOption::TYPE_HTML_CLASS),
			ElementOption::create(ElementOption::TYPE_HTML_STYLE),
		];

		$callback = function(yii\db\AfterSaveEvent $event) use ($options) {
			$model = $event->sender;
			foreach ($options as $option) {
				$model->link('options', $option);
				$option->save();
			}
		};

		$this->off(ActiveRecord::EVENT_AFTER_INSERT, $callback);
		$this->on(ActiveRecord::EVENT_AFTER_INSERT, $callback);
	}


	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$data = $this->getAttributes($this->safeAttributes());

			if (!$data || (!is_object($data) && !is_array($data))) {
				$data = new \stdClass();
			}

			$this->data = Json::encode($data);

			return true;
		}
		else {
			return false;
		}
	}

	public function afterSave($insert, $attributes)
	{
		$this->scenario = 'update';

		parent::afterSave($insert, $attributes);
		$this->parseData();
	}

	public function afterFind()
	{
		$this->scenario = 'update';

		parent::afterFind();
		$this->parseData();
	}

	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			$childs = $this->elements;
			self::deleteAll(['parent_element_id' => $this->element_id]);

			foreach ($childs as $child) {
				$child->afterDelete();
			}

			return true;
		}

		return false;
	}

	protected function parseData()
	{
		$attributes = Json::decode($this->data);
		$this->setActiveAttributes($attributes);
	}
}