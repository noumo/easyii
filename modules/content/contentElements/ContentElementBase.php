<?php
namespace yii\easyii\modules\content\contentElements;

use yii;
use yii\easyii\components\ActiveRecord;
use yii\easyii\modules\content\models\Item;
use yii\helpers\Json;

/**
 * Class ContentElement
 *
 * @property string $type
 * @property array $data
 * @property integer $order_num
 * @property integer $status
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
abstract class ContentElementBase extends ActiveRecord
{
	const STATUS_OFF = 0;
	const STATUS_ON = 1;

	public $layout = '@easyii/modules/content/views/layouts/contentElement';

	public $scenario = 'insert';

	public static $builtInElements = [
		'dynamic' => 'yii\easyii\modules\content\contentElements\dynamic\models\DynamicElement',
		'heading' => 'yii\easyii\modules\content\contentElements\heading\models\HeadingElement',
	];

	public static function tableName()
	{
		return 'easyii_content_element';
	}

	/**
	 * @param string $type
	 *
	 * @return static
	 */
	public static function create($type)
	{
		if (!array_key_exists($type, self::$builtInElements)) {
			throw new \InvalidArgumentException("The content element type of '$type' not found.");
		}

		$class = self::$builtInElements[$type];
		$element = new $class;

		return $element;
	}

	public static function instantiate($row)
	{
		$type = $row['type'];
		return self::create($type);
	}

	public function init()
	{
		parent::init();

		$this->type = array_search(get_class($this), self::$builtInElements);
	}

	public function render(yii\web\View $view)
	{
		$widget = ContentElementFactory::create($this);
		$widget->layout = 'contentElement';

		return $widget->run('template');
	}

	public function rules()
	{
		return [
			[['type'], 'safe'],
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

	public function formName()
	{
		if ($this->isNewRecord) {
			// Todo: Not so really unqiue!
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

	private function parseData()
	{
		$attributes = Json::decode($this->data);
		$this->setAttributes($attributes);
	}
}