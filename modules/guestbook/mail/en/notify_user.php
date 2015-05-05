<?php
use yii\helpers\Html;

$this->title = $subject;
?>

<p>The administration of <b><?= Yii::$app->request->serverName ?></b> replied to your message in the guestbook.</p>
<p>You can read it <?= Html::a('here', $link) ?>.</p>
<hr>
<p>This is an automatically generated email – please do not reply to it.</p>