<?php
/**
 * @var \yii\easyii\modules\content\models\Layout $model
 */
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

?>
<ul class="nav nav-pills">
	<?php if ($action === 'all') : ?>
		<li><a href="<?= Url::to(['/admin/'.$module.'/layout']) ?>"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii', 'Layouts') ?></a></li>

		<li class="active">
			<a href="<?= $this->context->getReturnUrl(['/admin/'.$module]) ?>">
				<?= Yii::t('easyii', 'List') ?>
			</a>
		</li>
	<?php else : ?>
		<li><a href="<?= Url::to(['/admin/'.$module.'/layout']) ?>"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii', 'Layouts') ?></a></li>
    <?php endif; ?>

    <li <?= ($action === 'new') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/item/new']) ?>"><?= Yii::t('easyii', 'New') ?></a></li>
</ul>
<br/>