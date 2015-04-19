<?php
use yii\helpers\Url;

$this->title = Yii::t('easyii/article', 'Articles');
?>
<?= $this->render('_menu', ['category' => $model]) ?>

<?php if(count($model->items)) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if(IS_ROOT) : ?>
                <th width="50">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Title') ?></th>
            <th width="120"><?= Yii::t('easyii', 'Views') ?></th>
            <th width="120"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model->items as $item) : ?>
            <tr data-id="<?= $item->primaryKey ?>">
                <?php if(IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><a href="<?= Url::to(['/admin/article/items/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>
                <td><?= $item->views ?></td>
                <td class="text-right">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="<?= Url::to(['/admin/article/items/up', 'id' => $item->primaryKey, 'category_id' => $model->primaryKey]) ?>" class="btn btn-default move-up" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
                        <a href="<?= Url::to(['/admin/article/items/down', 'id' => $item->primaryKey, 'category_id' => $model->primaryKey]) ?>" class="btn btn-default move-down" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
                        <a href="<?= Url::to(['/admin/article/items/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>