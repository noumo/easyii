<?php
use yii\easyii\assets\FieldsTableAsset;
use yii\easyii\components\CategoryWithFieldsModel;
use yii\helpers\Html;

$this->registerAssetBundle(FieldsTableAsset::className());
$this->registerJs('
var fieldTemplate = \'\
    <tr>\
        <td>'. Html::input('text', null, null, ['class' => 'form-control field-name']) .'</td>\
        <td>'. Html::input('text', null, null, ['class' => 'form-control field-title']) .'</td>\
        <td>\
            <select class="form-control field-type">'.str_replace("\n", "", Html::renderSelectOptions('', CategoryWithFieldsModel::$FIELD_TYPES)).'</select>\
        </td>\
        <td><textarea class="form-control field-options" placeholder="'.Yii::t('easyii', 'Type options with `comma` as delimiter').'" style="display: none;"></textarea></td>\
        <td class="text-right">\
            <div class="btn-group btn-group-sm" role="group">\
                <a href="#" class="btn btn-default move-up" title="'. Yii::t('easyii', 'Move up') .'"><span class="glyphicon glyphicon-arrow-up"></span></a>\
                <a href="#" class="btn btn-default move-down" title="'. Yii::t('easyii', 'Move down') .'"><span class="glyphicon glyphicon-arrow-down"></span></a>\
                <a href="#" class="btn btn-default color-red delete-field" title="'. Yii::t('easyii', 'Delete item') .'"><span class="glyphicon glyphicon-remove"></span></a>\
            </div>\
        </td>\
    </tr>\';
var fieldsWithOptions = ["' . implode('","', CategoryWithFieldsModel::$FIELDS_WITH_OPTIONS) . '"];
', \yii\web\View::POS_HEAD);

?>
<?= Html::button('<i class="glyphicon glyphicon-plus font-12"></i> '.Yii::t('easyii', 'Add field'), ['class' => 'btn btn-default', 'id' => 'addField']) ?>

<table id="categoryFields" class="table table-hover">
    <thead>
    <th>Name</th>
    <th><?= Yii::t('easyii', 'Title') ?></th>
    <th><?= Yii::t('easyii', 'Type') ?></th>
    <th><?= Yii::t('easyii', 'Options') ?></th>
    <th width="120"></th>
    </thead>
    <tbody>
    <?php foreach($model->fields as $field) : ?>
        <tr>
            <td><?= Html::input('text', null, $field->name, ['class' => 'form-control field-name']) ?></td>
            <td><?= Html::input('text', null, $field->title, ['class' => 'form-control field-title']) ?></td>
            <td>
                <select class="form-control field-type">
                    <?= Html::renderSelectOptions($field->type, CategoryWithFieldsModel::$FIELD_TYPES) ?>
                </select>
            </td>
            <td>
                <textarea class="form-control field-options" placeholder="<?= Yii::t('easyii', 'Type options with `comma` as delimiter') ?>" <?= (!in_array($field->type, CategoryWithFieldsModel::$FIELDS_WITH_OPTIONS)) ? 'style="display: none;"' : '' ?> ><?= is_array($field->options) ? implode(',', $field->options) : $field->options ?></textarea>
            </td>
            <td class="text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="#" class="btn btn-default move-up" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
                    <a href="#" class="btn btn-default move-down" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
                    <a href="#" class="btn btn-default color-red delete-field" title="<?= Yii::t('easyii', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= Html::button('<i class="glyphicon glyphicon-ok"></i> '.Yii::t('easyii', 'Save fields'), ['class' => 'btn btn-primary', 'id' => 'saveCategoryBtn']) ?>