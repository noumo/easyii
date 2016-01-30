<?php
/**
 * @var \yii\web\View                                                     $this
 * @var \yii\easyii\modules\content\models\contentElements\DynamicElement $element
 */

use yii\helpers\Html;

echo Html::activeHiddenInput($element, 'type');

$template = 'tr>
        <td>'. Html::input('text', null, $element->module, ['class' => 'form-control element-module']) .'</td>
        <td>'. Html::input('text', null, $element->function, ['class' => 'form-control element-function']) .'</td>

        <td><textarea class="form-control element-options" placeholder="'.Yii::t('easyii/content', 'Type options with `comma` as delimiter').'" style="display: none;"></textarea></td>
        <td class="text-right">
            <div class="btn-group btn-group-sm" role="group">
                <a href="#" class="btn btn-default move-up" title="'. Yii::t('easyii', 'Move up') .'"><span class="glyphicon glyphicon-arrow-up"></span></a>
                <a href="#" class="btn btn-default move-down" title="'. Yii::t('easyii', 'Move down') .'"><span class="glyphicon glyphicon-arrow-down"></span></a>
                <a href="#" class="btn btn-default color-red delete-element" title="'. Yii::t('easyii', 'Delete item') .'"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </td>
    </tr>';

$this->registerJs("var elementTemplate = '$template';");

echo $template;