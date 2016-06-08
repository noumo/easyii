<?php
namespace yii\easyii\modules\content\modules\contentElements\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use \yii\web\JsExpression;
use yii\web\View;

/**
 * Class SortableWidget
 * 
 * @see https://github.com/RubaXa/Sortable
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class SortableWidget extends \yii\base\Widget
{
	public $clientOptions = [
		'handle' => '> div:first',
		'items' => 'li.sortable-item',
		'maxLevels' => 1,
		'toleranceElement' => '',

		'forcePlaceholderSize' => true,
		'placeholder' => 'placeholder',
		'helper' =>	'clone',
		'opacity' => .6,
		#'revert' => 250,
		#'tabSize' => 25,
		#'tolerance' => 'pointer',
		'isTree' => false,
		#'expandOnHover' => 700,
		#'startCollapsed' => false
	];

	public $htmlOptions = [];

	public $items;

	public $render;

	public $prefix = 'sortable';

	public $rootId = null;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$this->registerAssets($this->view);

		$this->renderItems();
	}

	protected function renderItems()
	{
		$options = ['id' => $this->id, 'class' => "$this->prefix-list sortable", 'item' => [$this, 'renderItem']];

		if (isset($this->htmlOptions['class'])) {
			Html::addCssClass($options, $this->htmlOptions['class']);
			unset($this->htmlOptions['class']);
		}

		if (isset($this->htmlOptions['style'])) {
			Html::addCssClass($options, $this->htmlOptions['style']);
			unset($this->htmlOptions['style']);
		}

		$options = array_merge($options, $this->htmlOptions);

		$options['itemOptions'] = [
			'class' => 'sortable-item'
		];

		echo Html::ol($this->items, $options);
	}

	public function renderItem($item, $index)
	{
		if (is_callable($this->render)) {
			$content = call_user_func($this->render, $item, $index);
		}
		else {
			$content = Html::encode($item);
		}

		return $content;
	}

	protected function registerAssets(View $view)
	{
		if ($this->rootId !== null) {
			$this->clientOptions['rootID'] = $this->rootId;
		}
		$options = \yii\helpers\Json::htmlEncode($this->clientOptions);

		$view->registerJs("$('#$this->id').nestedSortable($options);");
	}
}