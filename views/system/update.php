<?php
use yii\helpers\Url;

$this->title = Yii::t('easyii', 'Update');
?>
<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to(['/admin/system']) ?>">
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?= Yii::t('easyii', 'Back') ?>
        </a>
    </li>
</ul>
<br>

<pre>
<?= $result ?>
</pre>
