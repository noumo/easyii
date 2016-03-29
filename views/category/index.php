<?php
use yii\easyii\components\CategoryModel;
use yii\helpers\Url;

\yii\bootstrap\BootstrapPluginAsset::register($this);

$this->title = Yii::$app->getModule('admin')->activeModules[$this->context->module->id]->title;

$baseUrl = '/admin/'.$this->context->moduleName;
?>

<?= $this->render('_menu') ?>

<?php if(sizeof($cats) > 0) : ?>
    <table class="table table-hover">
        <tbody>
            <?php foreach($cats as $cat) : ?>
                <tr>
                    <td width="50"><?= $cat->id ?></td>
                    <td style="padding-left:  <?= $cat->depth * 20 ?>px;">
                        <?php if(count($cat->children)) : ?>
                            <i class="caret"></i>
                        <?php endif; ?>
                        <?php if(!count($cat->children) || !empty(Yii::$app->controller->module->settings['itemsInFolder'])) : ?>
                            <a href="<?= Url::to([$baseUrl . $this->context->viewRoute, 'id' => $cat->id]) ?>" <?= ($cat->status == CategoryModel::STATUS_OFF ? 'class="smooth"' : '') ?>><?= $cat->title ?></a>
                        <?php else : ?>
                            <span <?= ($cat->status == CategoryModel::STATUS_OFF ? 'class="smooth"' : '') ?>><?= $cat->title ?></span>
                        <?php endif; ?>
                    </td>
                    <td width="120" class="text-right">
                        <div class="dropdown actions">
                            <i id="dropdownMenu<?= $cat->id ?>" data-toggle="dropdown" aria-expanded="true" title="<?= Yii::t('easyii', 'Actions') ?>" class="glyphicon glyphicon-menu-hamburger"></i>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu<?= $cat->id ?>">
                                <li><a href="<?= Url::to([$baseUrl.'/a/edit', 'id' => $cat->id]) ?>"><i class="glyphicon glyphicon-pencil font-12"></i> <?= Yii::t('easyii', 'Edit') ?></a></li>
                                <li><a href="<?= Url::to([$baseUrl.'/a/create', 'parent' => $cat->id]) ?>"><i class="glyphicon glyphicon-plus font-12"></i> <?= Yii::t('easyii', 'Add subcategory') ?></a></li>
                                <li role="presentation" class="divider"></li>
                                <li><a href="<?= Url::to([$baseUrl.'/a/up', 'id' => $cat->id]) ?>"><i class="glyphicon glyphicon-arrow-up font-12"></i> <?= Yii::t('easyii', 'Move up') ?></a></li>
                                <li><a href="<?= Url::to([$baseUrl.'/a/down', 'id' => $cat->id]) ?>"><i class="glyphicon glyphicon-arrow-down font-12"></i> <?= Yii::t('easyii', 'Move down') ?></a></li>
                                <li role="presentation" class="divider"></li>
                                <?php if($cat->status == CategoryModel::STATUS_ON) :?>
                                    <li><a href="<?= Url::to([$baseUrl.'/a/off', 'id' => $cat->id]) ?>" title="<?= Yii::t('easyii', 'Turn Off') ?>'"><i class="glyphicon glyphicon-eye-close font-12"></i> <?= Yii::t('easyii', 'Turn Off') ?></a></li>
                                <?php else : ?>
                                    <li><a href="<?= Url::to([$baseUrl.'/a/on', 'id' => $cat->id]) ?>" title="<?= Yii::t('easyii', 'Turn On') ?>"><i class="glyphicon glyphicon-eye-open font-12"></i> <?= Yii::t('easyii', 'Turn On') ?></a></li>
                                <?php endif; ?>
                                <li><a href="<?= Url::to([$baseUrl.'/a/delete', 'id' => $cat->id]) ?>" class="confirm-delete" data-reload="1" title="<?= Yii::t('easyii', 'Delete item') ?>"><i class="glyphicon glyphicon-remove font-12"></i> <?= Yii::t('easyii', 'Delete') ?></a></li>
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