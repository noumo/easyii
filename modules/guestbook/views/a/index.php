<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\easyii\modules\guestbook\models\Guestbook;

$this->title = Yii::t('easyii/guestbook', 'Guestbook');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <th width="50">#</th>
                <?php endif; ?>
                <th><?= Yii::t('easyii', $this->context->module->settings['enableTitle'] ? 'Title' : 'Text') ?></th>
                <th width="150"><?= Yii::t('easyii', 'Date') ?></th>
                <th width="100"><?= Yii::t('easyii/guestbook', 'Answer') ?></th>
                <th width="100"><?= Yii::t('easyii', 'Status') ?></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach($data->models as $item) : ?>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td>
                    <?php if($item->new) : ?>
                        <span class="label label-warning">NEW</span>
                    <?php endif; ?>
                    <a href="<?= Url::to(['/admin/'.$module.'/a/view', 'id' => $item->primaryKey]) ?>">
                        <?= ($item->title != '') ? $item->title : StringHelper::truncate($item->text, 120, '...') ?>
                    </a>
                </td>
                <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                <td>
                    <?php if($item->answer != '') : ?>
                        <span class="text-success"><?= Yii::t('easyii', 'Yes') ?></span>
                    <?php else : ?>
                        <span class="text-danger"><?= Yii::t('easyii', 'No') ?></span>
                    <?php endif; ?>
                </td>
                <td class="status">
                    <?= Html::checkbox('', $item->status == Guestbook::STATUS_ON, [
                        'class' => 'switch',
                        'data-id' => $item->primaryKey,
                        'data-link' => Url::to(['/admin/'.$module.'/a']),
                    ]) ?>
                </td>
                <td class="control"><a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
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