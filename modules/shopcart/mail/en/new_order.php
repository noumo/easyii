<?php
use yii\helpers\Html;

$this->title = $subject;
?>

<p>New order created <b>#<?= $order->id ?></b>.</p>
<p>You can view it <?= Html::a('here', $link) ?>.</p>