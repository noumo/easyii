<?php
use yii\helpers\Html;

$this->title = $subject;
?>
<p>Пользователь <b><?= $post->name ?></b> оставил сообщение в вашей гостевой книге.</p>
<p>Просмотреть его вы можете <?= Html::a('здесь', $link) ?>.</p>