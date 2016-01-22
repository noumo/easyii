<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Url;
use yii\helpers\StringHelper;

$this->title = Yii::t('easyii/content', 'Layout');

$module = $this->context->module->id;
?>
<?= $this->render('_menu', ['model' => $model]) ?>

<?php if($dataProvider->totalCount) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if(IS_ROOT) : ?>
                <th width="50">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Name') ?></th>
	        <th width="120"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataProvider->models as $item) : ?>
	        <tr data-id="<?= $item->primaryKey ?>">
		        <?php if(IS_ROOT) : ?>
			        <td><?= $item->primaryKey ?></td>
		        <?php endif; ?>
		        <td><a href="<?= Url::to(['edit', 'id' => $item->primaryKey]) ?>"><?= StringHelper::truncate(strip_tags($item->title), 128) ?></a></td>
		        <td>
			        <div class="btn-group btn-group-sm" role="group">
				        <a href="<?= Url::to(['up', 'id' => $item->primaryKey]) ?>" class="btn btn-default move-up" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
				        <a href="<?= Url::to(['down', 'id' => $item->primaryKey]) ?>" class="btn btn-default move-down" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
				        <a href="<?= Url::to(['delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
			        </div>
		        </td>
	        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>