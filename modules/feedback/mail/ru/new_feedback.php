<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = $subject;
?>
<p>Пользователь <b><?= ArrayHelper::getValue($feedback, 'name') ?></b> оставил сообщение в вашей гостевой книге.</p>
<p>Просмотреть его вы можете <?= Html::a('здесь', $link) ?>.</p>
<hr>
<p>Это автоматическое сообщение и на него не нужно отвечать.</p>
