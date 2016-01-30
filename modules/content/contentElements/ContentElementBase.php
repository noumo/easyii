<?php
namespace yii\easyii\modules\content\contentElements;

use yii;
use yii\easyii\components\ActiveRecord;
use yii\easyii\modules\content\models\Item;
use yii\helpers\Json;

/**
 * Class ContentElement
 *
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

	public static $builtInElements = [
		'dynamic' => 'yii\easyii\modules\content\contentElements\dynamic\models\DynamicElement',
		'header' => 'yii\easyii\modules\content\contentElements\header\models\HeaderElement',
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
		$content = $view->renderFile($this->getViewFile(), ['model' => $this]);

		return $view->render($this->layout, ['content' => $content], $this);
	}

	/**
	 * Returns the directory containing the view files for this widget.
	 * The default implementation returns the 'views' subdirectory under the directory containing the widget class file.
	 * @return string the directory containing the view files for this widget.
	 */
	public function getViewFile()
	{
		$class = new \ReflectionClass($this);

		return __DIR__ . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR . 'views'  . DIRECTORY_SEPARATOR . 'template.php';
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

		return "Element[$this->type:$unique]";
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
		parent::afterSave($insert, $attributes);

		$this->parseData();
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->parseData();
	}

	private function parseData()
	{
		$attributes = Json::decode($this->data);
		$this->setAttributes($attributes);
	}
}