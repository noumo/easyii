<?php
use yii\helpers\Url;

$this->title = Yii::t('easyii/subscribe', 'History');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('easyii/subscribe', 'Subject') ?></th>
                <th width="150"><?= Yii::t('easyii', 'Date') ?></th>
                <th width="120"><?= Yii::t('easyii/subscribe', 'Sent') ?></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach($data->models as $item) : ?>
            <tr>
                <td><?= $item->primaryKey ?></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/a/view', 'id' => $item->primaryKey]) ?>"><?= $item->subject ?></a></td>
                <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                <td><?= $item->sent ?></td>
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