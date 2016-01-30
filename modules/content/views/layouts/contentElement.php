<?php
/**
 * @var $content
 */
?>

<tr>\
	<td>
		<?= $content ?>
	</td>
	<td class="text-right">
		<div class="btn-group btn-group-sm" role="group">
			<a href="#" class="btn btn-default move-up" title="'. Yii::t('easyii', 'Move up') .'"><span class="glyphicon glyphicon-arrow-up"></span></a>
			<a href="#" class="btn btn-default move-down" title="'. Yii::t('easyii', 'Move down') .'"><span class="glyphicon glyphicon-arrow-down"></span></a>
			<a href="#" class="btn btn-default color-red delete-field" title="'. Yii::t('easyii', 'Delete item') .'"><span class="glyphicon glyphicon-remove"></span></a>
		</div>
	</td>
</tr>