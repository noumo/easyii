<?php
$this->title = Yii::t('easyii/text', 'Texts');
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <th width="30">#</th>
                <?php endif; ?>
                <th><?= Yii::t('easyii', 'Text') ?></th>
                <?php if(IS_ROOT) : ?>
                    <th><?= Yii::t('easyii', 'Slug') ?></th>
                    <th width="30"></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
    <?php foreach($data->models as $item) : ?>
            <tr>
                <?php if(IS_ROOT) : ?>
                <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><a href="/admin/text/a/edit/<?= $item->primaryKey ?>"><?= $item->text ?></a></td>
                <?php if(IS_ROOT) : ?>
                    <td><?= $item->slug ?></td>
                    <td><a href="/admin/text/a/delete/<?= $item->primaryKey ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
                <?php endif; ?>
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