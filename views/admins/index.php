<?php
$this->title = Yii::t('easyii', 'Admins');
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="30">#</th>
            <th><?= Yii::t('easyii', 'Username') ?></th>
            <th width="30"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $admin) : ?>
            <tr>
                <td><?= $admin->admin_id ?></td>
                <td><a href="/admin/admins/edit/<?= $admin->admin_id ?>"><?= $admin->username ?></a></td>
                <td><a href="/admin/admins/delete/<?= $admin->admin_id ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
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