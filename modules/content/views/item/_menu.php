<?php
/**
 * @var \yii\easyii\modules\content\models\Layout $model
 */
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <?php if($model == null) : ?>
        <li><a href="<?= Url::to(['/admin/'.$module.'/item/all']) ?>"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii', 'All') ?></a></li>
    <?php else : ?>
        <li><a href="<?= Url::to(['/admin/'.$module.'/layout']) ?>"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii', $model->title) ?></a></li>
    <?php endif; ?>

    <li <?= ($action === 'new') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/item/new']) ?>"><?= Yii::t('easyii', 'New') ?></a></li>
</ul>
<br/>