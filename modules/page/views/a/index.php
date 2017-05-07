<?php
use yii\easyii\components\CategoryModel;
use yii\helpers\Url;

\yii\bootstrap\BootstrapPluginAsset::register($this);

$this->title = Yii::t('easyii/page', 'Pages');

$module = $this->context->module->id;
$baseUrl = '/admin/' . $module;
?>

<?= $this->render('_menu') ?>

<?php if(sizeof($pages) > 0) : ?>
    <table class="table table-hover">
        <tbody>
        <?php foreach($pages as $page) : ?>
            <tr>
                <td width="50"><?= $page->id ?></td>
                <td style="padding-left:  <?= $page->depth * 20 ?>px;">
                    <?php if(count($page->children)) : ?>
                        <i class="caret"></i>
                    <?php endif; ?>
                    <a href="<?= Url::to(['/admin/page/a/edit', 'id' => $page->id]) ?>" <?= ($page->status == CategoryModel::STATUS_OFF ? 'class="smooth"' : '') ?>><?= $page->title ?></a>
                </td>
                <td width="120" class="text-right">
                    <div class="dropdown actions">
                        <i id="dropdownMenu<?= $page->id ?>" data-toggle="dropdown" aria-expanded="true" title="<?= Yii::t('easyii', 'Actions') ?>" class="glyphicon glyphicon-menu-hamburger"></i>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu<?= $page->id ?>">
                            <li><a href="<?= Url::to(['/admin/page/a/create', 'parent' => $page->id]) ?>"><i class="glyphicon glyphicon-plus font-12"></i> <?= Yii::t('easyii/page', 'Add subpage') ?></a></li>
                            <li><a href="<?= Url::to(['/admin/page/a/copy', 'id' => $page->id]) ?>"><i class="glyphicon glyphicon-duplicate font-12"></i> <?= Yii::t('easyii/page', 'Copy page') ?></a></li>
                            <li role="presentation" class="divider"></li>
                            <li><a href="<?= Url::to(['/admin/page/a/up', 'id' => $page->id]) ?>"><i class="glyphicon glyphicon-arrow-up font-12"></i> <?= Yii::t('easyii', 'Move up') ?></a></li>
                            <li><a href="<?= Url::to(['/admin/page/a/down', 'id' => $page->id]) ?>"><i class="glyphicon glyphicon-arrow-down font-12"></i> <?= Yii::t('easyii', 'Move down') ?></a></li>
                            <li role="presentation" class="divider"></li>
                            <?php if($page->status == CategoryModel::STATUS_ON) :?>
                                <li><a href="<?= Url::to(['/admin/page/a/off', 'id' => $page->id]) ?>" title="<?= Yii::t('easyii', 'Turn Off') ?>'"><i class="glyphicon glyphicon-eye-close font-12"></i> <?= Yii::t('easyii', 'Turn Off') ?></a></li>
                            <?php else : ?>
                                <li><a href="<?= Url::to(['/admin/page/a/on', 'id' => $page->id]) ?>" title="<?= Yii::t('easyii', 'Turn On') ?>"><i class="glyphicon glyphicon-eye-open font-12"></i> <?= Yii::t('easyii', 'Turn On') ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?= Url::to(['/admin/page/a/delete', 'id' => $page->id]) ?>" class="confirm-delete" data-reload="1" title="<?= Yii::t('easyii', 'Delete item') ?>"><i class="glyphicon glyphicon-remove font-12"></i> <?= Yii::t('easyii', 'Delete') ?></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>