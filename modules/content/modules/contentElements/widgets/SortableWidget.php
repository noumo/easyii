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
		'handle' => 'div',
		'items' => 'li',
		'toleranceElement' => '> div',
	];

	public $items;

	public $render;

	public $prefix = 'sortable';

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$this->registerAssets($this->view);

		echo Html::beginTag('div', ['class' => "$this->prefix-list"]);

		$this->renderItems();

		echo Html::endTag('div');
	}

	protected function renderItems()
	{
		echo Html::ol($this->items, ['id' => $this->id, 'class' => 'sortable', 'item' => [$this, 'renderItem']]);
	}

	public function renderItem($item, $index)
	{
		if (is_callable($this->render)) {
			$content = call_user_func($this->render, $item, $index);
		}
		else {
			$content = Html::encode($item);
		}

		$content .= Html::tag('i', '✖', ['class' => 'js-remove']);

		$content = Html::tag('div', $content);
		return Html::tag('li', $content);
	}

	protected function registerAssets(View $view)
	{
		$options = \yii\helpers\Json::htmlEncode($this->clientOptions);

		$view->registerJs("$('#$this->id').nestedSortable($options);");

		$view->registerJsFile('http://code.jquery.com/ui/1.11.4/jquery-ui.min.js');
		$view->registerJsFile('https://cdn.rawgit.com/ilikenwf/nestedSortable/2.0alpha/jquery.mjs.nestedSortable.js');
	}
}