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
									var el = document.createElement('li');
									el.innerHTML = data + '<i class=\"js-remove\">✖</i>';
									$('#$id').append(el);
								}
							});
						});
					});
				});

			$('#$id').closest('form').on('submit', function() {
				var childs = $('#$id').children('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
				for (var i in childs) {
					var child = childs[i],
						id = child.id ? child.id : child.item_id
					$('#element-' + id + '-parent_element_id').val(child.parent_id);
				}
			});

			$('#$id .js-remove').on('click', function (evt) {
				var el = $(evt.target).closest('li'); // get dragged item
				if (el) {
					var id = el.data('element-id');
					$('#element-'+id+'-scenario').val('delete');
					el.hide();
				}
			});");

		parent::registerAssets($view);
	}

}