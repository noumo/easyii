<?php

namespace yii\easyii\modules\content\modules\contentElements\widgets;

use yii\web\View;

class EditableList extends SortableWidget
{
	public $addButton;

	public $prefix = 'editable';

	public $modalSelector;

	public $templateUrl;
	public $deleteUrl;

	public function init()
	{
		parent::init();
	}

	protected function registerAssets(View $view)
	{
		EditableListAssets::register($view);

		$id = $this->id;
		$modalSelector = $this->modalSelector;
		$templateUrl = $this->templateUrl;
		$deleteUrl = $this->deleteUrl;

		$view->registerJs("$('#$id').editableList({deleteUrl: '$deleteUrl', templateUrl: '$templateUrl', modalSelector: '#$modalSelector'})");

		$listSelector = ".$this->prefix-list";
		$view->registerJs("
			$('$listSelector').parents('$listSelector:last').closest('form').on('submit', function() {
				var childs = $(this).find('$listSelector:first').nestedSortable('toArray', {startDepthCount: 0});
				for (var i in childs) {
					var child = childs[i],
						id = child.id ? child.id : child.item_id
					$('#element-' + id + '-parent_element_id').val(child.parent_id);
				}
			});", View::POS_READY, 'saveList');

		parent::registerAssets($view);
	}

}