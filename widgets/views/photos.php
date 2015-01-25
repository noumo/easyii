<?php
use yii\helpers\Html;

$module = $this->context->module;
$item_id = $this->context->item_id;

$photoTemplate = '<tr data-id="{{photo_id}}">'.(IS_ROOT ? '<td>{{photo_id}}</td>' : '').'\
    <td><a href="{{photo_image}}" class="colorbox" title="{{photo_description}}"><img class="photo-thumb" id="photo-{{photo_id}}" src="{{photo_thumb}}"></a></td>\
    <td>\
        <textarea class="form-control photo-description">{{photo_description}}</textarea>\
        <a href="#" class="btn btn-sm btn-primary disabled save-photo-description">'. Yii::t('easyii', 'Save') .'</a>\
    </td>\
    <td class="control vtop">\
        <div class="btn-group btn-group-sm" role="group">\
            <a href="/admin/photos/up/{{photo_id}}?module='. $module .'&item_id='. $item_id .'" class="btn btn-default move-up" title="'. Yii::t('easyii', 'Move up') .'"><span class="glyphicon glyphicon-arrow-up"></span></a>\
            <a href="/admin/photos/down/{{photo_id}}?module='. $module .'&item_id='. $item_id .'" class="btn btn-default move-down" title="'. Yii::t('easyii', 'Move down') .'"><span class="glyphicon glyphicon-arrow-down"></span></a>\
            <a href="#" class="btn btn-default change-image-button" title="'. Yii::t('easyii', 'Change image') .'"><span class="glyphicon glyphicon-floppy-disk"></span></a>\
            <a href="/admin/photos/delete/{{photo_id}}" class="btn btn-default color-red delete-photo" title="'. Yii::t('easyii', 'Delete item') .'"><span class="glyphicon glyphicon-remove"></span></a>\
            <input type="file" name="Photo[image]" class="change-image-input hidden">\
        </div>\
    </td>\
</tr>';
$this->registerJs("
var photoTemplate = '{$photoTemplate}';
", \yii\web\View::POS_HEAD);
$photoTemplate = str_replace('>\\', '>', $photoTemplate);
?>
<button id="photo-upload" class="btn btn-success text-uppercase"><span class="glyphicon glyphicon-arrow-up"></span> <?= Yii::t('easyii', 'Upload')?></button>

<table id="photo-table" class="table table-hover" style="display: <?= count($photos) ? 'table' : 'none' ?>;">
    <thead>
    <tr>
        <?php if(IS_ROOT) : ?>
        <th width="30">#</th>
        <?php endif; ?>
        <th width="150"><?= Yii::t('easyii', 'Image') ?></th>
        <th><?= Yii::t('easyii', 'Description') ?></th>
        <th width="150"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($photos as $photo) : ?>
        <?= str_replace(
            ['{{photo_id}}', '{{photo_thumb}}', '{{photo_image}}', '{{photo_description}}'],
            [$photo->primaryKey, $photo->thumb, $photo->image, $photo->description],
            $photoTemplate)
        ?>
    <?php endforeach; ?>
    </tbody>
</table>
<p class="empty" style="display: <?= count($photos) ? 'none' : 'block' ?>;"><?= Yii::t('easyii', 'No photos uploaded yet') ?>.</p>

<?= Html::beginForm('/admin/photos/upload?module='.$module.'&item_id='.$item_id, 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::fileInput('Photo[image]', null, [
    'id' => 'photo-file',
    'class' => 'hidden',
    'data-module' => $module,
    'data-id' => $item_id,
])
?>
<?php Html::endForm() ?>
