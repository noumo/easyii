<?php
/**
 * @var Item $item
 * @var $module
 */

use yii\easyii\modules\content\models\base\ItemModel;
use yii\easyii\modules\content\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<tr data-id="<?= $item->primaryKey ?>">
	<?php if(IS_ROOT) : ?>
		<td><?= $item->primaryKey ?></td>
	<?php endif; ?>

	<td style="padding-left:  <?= $item->depth * 20 ?>px;">

		<i class="caret" style="opacity: <?= count($item->children) ?>"></i>

		<?php if(!count($item->children) || !empty(Yii::$app->controller->module->settings['itemsInFolder'])) : ?>
			<a href="<?= Url::to(['/admin/'.$module.'/item/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a>
		<?php else : ?>
			<span <?= ($item->status == ItemModel::STATUS_OFF ? 'class="smooth"' : '') ?>><?= $item->title ?></span>
		<?php endif; ?>
	</td>

	<td class="nav">
		<?= Html::checkbox('', $item->nav == Item::NAV_ON, [
			'class' => 'switch',
			'data-id' => $item->primaryKey,
			'data-link' => Url::to(['/admin/'.$module.'/nav']),
		]) ?>
	</td>
	<td class="status">
		<?= Html::checkbox('', $item->status == Item::STATUS_ON, [
			'class' => 'switch',
			'data-id' => $item->primaryKey,
			'data-link' => Url::to(['/admin/'.$module.'/item']),
		]) ?>
	</td>
	<td width="120" class="text-right">
		<div class="dropdown actions">
			<i id="dropdownMenu<?= $item->primaryKey ?>" data-toggle="dropdown" aria-expanded="true" title="<?= Yii::t('easyii', 'Actions') ?>" class="glyphicon glyphicon-menu-hamburger"></i>
			<ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu<?= $item->primaryKey ?>">
				<li><a href="<?= Url::to(['edit', 'id' => $item->primaryKey]) ?>"><i class="glyphicon glyphicon-pencil font-12"></i> <?= Yii::t('easyii', 'Edit') ?></a></li>
				<li><a href="<?= Url::to(['new', 'parent' => $item->primaryKey]) ?>"><i class="glyphicon glyphicon-plus font-12"></i> <?= Yii::t('easyii', 'Add sub item') ?></a></li>
				<li role="presentation" class="divider"></li>
				<li><a href="<?= Url::to(['up', 'id' => $item->primaryKey]) ?>"><i class="glyphicon glyphicon-arrow-up font-12"></i> <?= Yii::t('easyii', 'Move up') ?></a></li>
				<li><a href="<?= Url::to(['down', 'id' => $item->primaryKey]) ?>"><i class="glyphicon glyphicon-arrow-down font-12"></i> <?= Yii::t('easyii', 'Move down') ?></a></li>
				<li role="presentation" class="divider"></li>
				<li><a href="<?= Url::to(['delete', 'id' => $item->primaryKey]) ?>" class="confirm-delete" data-reload="1" title="<?= Yii::t('easyii', 'Delete item') ?>"><i class="glyphicon glyphicon-remove font-12"></i> <?= Yii::t('easyii', 'Delete') ?></a></li>
			</ul>
		</div>
	</td>
</tr>