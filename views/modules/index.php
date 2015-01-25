<?php
use yii\easyii\models\Module;
use yii\helpers\Html;

$this->title = Yii::t('easyii', 'Modules');
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="30">#</th>
            <th><?= Yii::t('easyii', 'Name') ?></th>
            <th><?= Yii::t('easyii', 'Title') ?></th>
            <th width="150"><?= Yii::t('easyii', 'Icon') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Status') ?></th>
            <th width="150"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $module) : ?>
            <tr>
                <td><?= $module->primaryKey ?></td>
                <td><a href="/admin/modules/edit/<?= $module->primaryKey ?>" title="<?= Yii::t('easyii', 'Edit') ?>"><?= $module->name ?></a></td>
                <td><?= $module->title ?></td>
                <td>
                    <?php if($module->icon) : ?>
                        <span class="glyphicon glyphicon-<?= $module->icon ?>"></span> <?= $module->icon ?>
                    <?php endif; ?>
                </td>
                <td class="status">
                    <?= Html::checkbox('', $module->status == Module::STATUS_ON, [
                        'class' => 'switch',
                        'data-id' => $module->primaryKey,
                        'data-link' => '/admin/modules/',
                        'data-reload' => '1'
                    ]) ?>
                </td>
                <td class="control">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="/admin/modules/up/<?= $module->primaryKey ?>" class="btn btn-default" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
                        <a href="/admin/modules/down/<?= $module->primaryKey ?>" class="btn btn-default" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
                        <a href="/admin/modules/delete/<?= $module->primaryKey ?>" class="btn btn-default confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?= yii\widgets\LinkPager::widget([
            'pagination' => $data->pagination
        ]) ?>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>