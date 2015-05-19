<?php
use yii\helpers\Html;

$this->title = $subject;
?>
<p>User <b><?= $feedback->name ?></b> leaved message in your guestbook.</p>
<p>You can view it <?= Html::a('here', $link) ?>.</p>