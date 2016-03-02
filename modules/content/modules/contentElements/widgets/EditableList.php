<?php

namespace yii\easyii\modules\content\modules\contentElements\widgets;

use yii\helpers\Html;
use yii\web\View;

class EditableList extends SortableWidget
{
	public $addButton;

	public $prefix = 'editable';

	public $modalSelector;

	public function init()
	{
		parent::init();

		$id = $this->id;

		$this->clientOptions = [
		];
	}

	protected function registerAssets(View $view)
	{
		EditableListAssets::register($view);

		$id = $this->id;
		$modalSelector = $this->modalSelector;

		$view->registerJs("$('#$modalSelector')
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

			$('.js-remove').on('click', function (evt) {
				var el = $(evt.target).closest('.ui-sortable-handle'); // get dragged item
				el && el.remove();
			});");

		parent::registerAssets($view);
	}

}