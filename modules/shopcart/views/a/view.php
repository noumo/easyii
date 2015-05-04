<?php
use yii\easyii\modules\shopcart\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('easyii/shopcart', 'Order') . ' #' . $order->primaryKey;
$this->registerCss('.shopcart-view dt{margin-bottom: 10px;}');

$this->registerJs('
    $("#order-status").change(function(){
        location.href = "' . Url::to(['/admin/shopcart/a/status', 'id' => $order->primaryKey]) . '?status="+$(this).val();
    });
');

$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>

<dl class="dl-horizontal shopcart-view">
    <dt><?= Yii::t('easyii', 'Status') ?></dt>
    <dd><?= Html::dropDownList('status', $order->status, Order::states(), ['id' => 'order-status'])?></dd>

    <dt><?= Yii::t('easyii', 'Name') ?></dt>
    <dd><?= $order->name ?></dd>

    <dt><?= Yii::t('easyii/shopcart', 'Address') ?></dt>
    <dd><?= $order->address ?></dd>

    <dt><?= Yii::t('easyii/shopcart', 'Phone') ?></dt>
    <dd><?= $order->phone ?></dd>

    <dt><?= Yii::t('easyii', 'E-mail') ?></dt>
    <dd><?= $order->email ?></dd>

    <dt><?= Yii::t('easyii', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($order->time, 'medium') ?></dd>

    <dt><?= Yii::t('easyii/shopcart', 'Comment') ?></dt>
    <dd><?= nl2br($order->comment) ?></dd>
</dl>
<hr>
<h3><?= Yii::t('easyii/shopcart', 'Items') ?></h3>
<table class="table table-bordered">
    <thead>
        <th><?= Yii::t('easyii', 'Title') ?></th>
        <th><?= Yii::t('easyii/shopcart', 'Options') ?></th>
        <th width="80"><?= Yii::t('easyii/shopcart', 'Count') ?></th>
        <th width="80"><?= Yii::t('easyii/shopcart', 'Discount') ?></th>
        <th width="150"><?= Yii::t('easyii/shopcart', 'Price') ?></th>
        <th width="30"></th>
    </thead>
    <tbody>
        <?php foreach($goods as $good) : ?>
            <tr>
                <td><?= Html::a($good->item->title, ['/admin/shop/items/edit', 'id' => $good->item->primaryKey]) ?></td>
                <td><?= $good->options ?></td>
                <td><?= $good->count ?></td>
                <td><?= $good->discount ?></td>
                <td>
                    <?php if($good->discount) : ?>
                        <b><?= round($good->price * (1 - $good->discount / 100)) ?></b>
                        <strike><small class="smooth"><?= $good->price ?></small></strike>
                    <?php else : ?>
                        <b><?= $good->price ?></b>
                    <?php endif; ?>
                </td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/goods/delete', 'id' => $good->primaryKey]) ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h2 class="text-right"><small><?= Yii::t('easyii/shopcart', 'Total') ?>:</small> <?= $order->cost ?></h2>
