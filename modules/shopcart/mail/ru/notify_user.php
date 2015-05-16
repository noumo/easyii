<?php
use yii\helpers\Html;

$this->title = $subject;

$total = 0;
?>

<p>Статус вашего заказа <b>#<?= $order->primaryKey ?></b> изменен.</p>
<p>Новый статус: <b><?= $order->statusName ?></b></p>
<br>
<table border="1">
    <tr>
        <th>Товар</th>
        <th>Кол-во</th>
        <th>Цена</th>
        <th>Всего</th>
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
            <b>Итого: <?= $total ?></b>
        </td>
    </tr>
</table>
<p>Посмотреть свой заказ на сайте Вы можете <?= Html::a('здесь', $link) ?>.</p>
<hr>
<p>Это автоматическое сообщение и на него не нужно отвечать.</p>