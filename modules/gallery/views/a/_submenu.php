<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'photos') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/gallery/a/photos', 'id' => $model->primaryKey]) ?>"><span class="glyphicon glyphicon-camera"></span> <?= Yii::t('easyii', 'Photos') ?></a></li>
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/gallery/a/edit', 'id' => $model->primaryKey]) ?>"><?= Yii::t('easyii/gallery', 'Edit album') ?></a></li>
</ul>
<br>