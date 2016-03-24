<?php
/**
 * @var \yii\web\View $this
 */
use yii\easyii\components\CategoryModel;
use yii\helpers\Url;

\yii\bootstrap\BootstrapPluginAsset::register($this);
$this->title = Yii::$app->getModule('admin')->activeModules[$this->context->module->id]->title;

$baseUrl = '/admin/'.$this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if(sizeof($cats) > 0) : ?>
    <table class="table table-hover">
        <tbody>
            <?php foreach($cats as $cat) : ?>
                <tr>
                    <td width="50"><?= $cat->category_id ?></td>
                    <td style="padding-left:  <?= $cat->depth * 20 ?>px;">
	                    <i class="caret" style="opacity: <?= count($cat->children) ?>"></i>

	                    <?php if(!count($cat->children) || !empty(Yii::$app->controller->module->settings['itemsInFolder'])) : ?>
                            <a href="<?= Url::to([$baseUrl . $this->context->viewRoute, 'id' => $cat->category_id]) ?>" <?= ($cat->status == CategoryModel::STATUS_OFF ? 'class="smooth"' : '') ?>><?= $cat->title ?></a>
                        <?php else : ?>
                            <span <?= ($cat->status == CategoryModel::STATUS_OFF ? 'class="smooth"' : '') ?>><?= $cat->title ?></span>
                        <?php endif; ?>
                    </td>
                    <td width="120" class="text-right">
                        <div class="dropdown actions">
                            <i id="dropdownMenu<?= $cat->category_id ?>" data-toggle="dropdown" aria-expanded="true" title="<?= Yii::t('easyii', 'Actions') ?>" class="glyphicon glyphicon-menu-hamburger"></i>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu<?= $cat->category_id ?>">
                                <li><a href="<?= Url::to(['edit', 'id' => $cat->category_id]) ?>"><i class="glyphicon glyphicon-pencil font-12"></i> <?= Yii::t('easyii', 'Edit') ?></a></li>
                                <li><a href="<?= Url::to(['create', 'parent' => $cat->category_id]) ?>"><i class="glyphicon glyphicon-plus font-12"></i> <?= Yii::t('easyii', 'Add subcategory') ?></a></li>
                                <li role="presentation" class="divider"></li>
                                <li><a href="<?= Url::to(['up', 'id' => $cat->category_id]) ?>"><i class="glyphicon glyphicon-arrow-up font-12"></i> <?= Yii::t('easyii', 'Move up') ?></a></li>
                                <li><a href="<?= Url::to(['down', 'id' => $cat->category_id]) ?>"><i class="glyphicon glyphicon-arrow-down font-12"></i> <?= Yii::t('easyii', 'Move down') ?></a></li>
                                <li role="presentation" class="divider"></li>
                                <?php if($cat->status == CategoryModel::STATUS_ON) :?>
                                    <li><a href="<?= Url::to(['off', 'id' => $cat->category_id]) ?>" title="<?= Yii::t('easyii', 'Turn Off') ?>'"><i class="glyphicon glyphicon-eye-close font-12"></i> <?= Yii::t('easyii', 'Turn Off') ?></a></li>
                                <?php else : ?>
                                    <li><a href="<?= Url::to(['on', 'id' => $cat->category_id]) ?>" title="<?= Yii::t('easyii', 'Turn On') ?>"><i class="glyphicon glyphicon-eye-open font-12"></i> <?= Yii::t('easyii', 'Turn On') ?></a></li>
                                <?php endif; ?>
                                <li><a href="<?= Url::to(['delete', 'id' => $cat->category_id]) ?>" class="confirm-delete" data-reload="1" title="<?= Yii::t('easyii', 'Delete item') ?>"><i class="glyphicon glyphicon-remove font-12"></i> <?= Yii::t('easyii', 'Delete') ?></a></li>
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