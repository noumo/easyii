<?php
use yii\helpers\Html;
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">

	<?php if ($action === 'index') : ?>
		<li>
			<a href="<?= Url::to(['/admin/' . $module]) ?>">
				<i class="glyphicon glyphicon-chevron-left font-12"></i>
				<?= Yii::t('easyii', 'List') ?>
			</a>
		</li>
	<?php else: ?>
		<li>
			<a href="<?= $this->context->getReturnUrl(['/admin/' . $module]) ?>">
				<i class="glyphicon glyphicon-chevron-left font-12"></i>
				<?= Yii::t('easyii/content', 'Layouts') ?>
			</a>
		</li>
	<?php endif; ?>

	<li <?= ($action === 'create') ? 'class="active"' : '' ?>>
		<?= Html::a(Yii::t('easyii/content', 'Create layout'), ['create']) ?>
	</li>
</ul>

<br/>