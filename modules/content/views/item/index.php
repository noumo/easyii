<?php
/**
 * @var \yii\web\View $this
 */

\yii\bootstrap\BootstrapPluginAsset::register($this);
$this->title = Yii::t('easyii/content', 'Catalog');

$module = $this->context->module->id;
?>
<?= $this->render('_menu', ['model' => $model]) ?>

<?php if(count($model->items)) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if(IS_ROOT) : ?>
                <th width="50">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Name') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Navigation') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Status') ?></th>
            <th width="120"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model->items as $item) : ?>
	        <?php $this->render('_listItem', ['item' => $item, 'module' => $module])?>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>