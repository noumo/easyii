<?php
use yii\helpers\Html;

$this->title = $subject;

$total = 0;
?>

<p>The status of your order <b>#<?= $order->primaryKey ?></b> changed.</p>
<p>New status: <b><?= $order->statusName ?></b></p>
<br>
<table border="1">
    <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>Unit price</th>
        <th>Total</th>
    </tr>
    <?php foreach($order->goods as $good) : ?>
        <?php
            $price = $good->discount ? round($good->price * (1 - $good->discount / 100)) : $good->price;
            $unitTotal = $good->count * $price;
            $total += $unitTotal;
        ?>
        <tr>
            <td><?= $good->item->title ?> <?= $good->options ? "($good->options)" : '' ?></td>
            <td><?= $good->count ?></td>
            <td><?= $price ?></td>
            <td><?= $unitTotal ?></td>
        </tr>
    <?php endforeach?>
    <tr>
        <td colspan="5" align="right">
            <b>Total: <?= $total ?></b>
        </td>
    </tr>
</table>
<p>You can view it <?= Html::a('here', $link) ?>.</p>
<hr>
<p>This is an automatically generated email – please do not reply to it.</p>