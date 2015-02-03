<?php
use yii\helpers\Html;
use yii\easyii\modules\catalog\models\Category;

$this->title = Yii::t('easyii/catalog', 'Catalog');
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if(IS_ROOT) : ?>
                <th width="30">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Name') ?></th>
            <th width="100"><?= Yii::t('easyii/catalog', 'Items') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Status') ?></th>
            <th width="<?= IS_ROOT ? 160 : 120 ?>"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $item) : ?>
            <tr data-id="<?= $item->primaryKey ?>">
                <?php if(IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><a href="/admin/catalog/items/<?= $item->primaryKey ?>"><?= $item->title ?></a></td>
                <td><?= $item->item_count ?></td>
                <td class="status">
                    <?= Html::checkbox('', $item->status == Category::STATUS_ON, [
                        'class' => 'switch',
                        'data-id' => $item->primaryKey,
                        'data-link' => '/admin/catalog/a/'
                    ]) ?>
                </td>
                <td class="control">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="/admin/catalog/a/up/<?= $item->primaryKey ?>" class="btn btn-default move-up" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
                        <a href="/admin/catalog/a/down/<?= $item->primaryKey ?>" class="btn btn-default move-down" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
                        <a href="/admin/catalog/a/edit/<?= $item->primaryKey ?>" class="btn btn-default" title="<?= Yii::t('easyii/catalog', 'Edit category') ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                        <?php if(IS_ROOT) : ?>
                        <a href="/admin/catalog/a/delete/<?= $item->primaryKey ?>" class="btn btn-default confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>