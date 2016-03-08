<?php

namespace yii\easyii\modules\content\modules\contentElements\widgets;

use yii\web\View;

class EditableList extends SortableWidget
{
	public $addButton;

	public $prefix = 'editable';

	public $modalSelector;

	public function init()
	{
		parent::init();
	}

	protected function registerAssets(View $view)
	{
		EditableListAssets::register($view);

		$id = $this->id;
		$modalSelector = $this->modalSelector;

		$view->registerJs("
			$('#$modalSelector')
				.off('show.bs.modal')
				.on('show.bs.modal', function(){

					var parentId = $(this).data('parent-id');;

					$(this).find('.content').load($(this).data('list-source'), '', function() {

						$(this).find('button[data-content-element]').on('click', function(){
							var type = $(this).data('content-element');

							$.ajax({
								method: 'GET',
								url: $('#$modalSelector').data('template-source'),
								data: {type: type, parentId: parentId},
								success: function(data) {
									$('#$id').append(data);
								}
							});
						});
					});
				});

			var list = $('#$id');

			list.find('.js-remove').on('click', function (evt) {
				var el = $(evt.target).closest('li'); // get dragged item
				if (el) {
					var id = el.data('element-id');
					$('#element-'+id+'-scenario').val('delete');
					el.hide();
				}
			});

			list.on('click', '.move-up', function(){
				var current = $(this).closest('li');
				var previos = current.prev();
				if(previos.get(0)){
					previos.before(current);
				}
				return false;
			});

			list.on('click', '.move-down', function(){
				var current = $(this).closest('li');
				var next = current.next();
				if(next.get(0)){
					next.after(current);
				}
				return false;
			});
			");

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