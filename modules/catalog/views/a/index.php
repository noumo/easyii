<?php
use yii\helpers\Url;

\yii\bootstrap\BootstrapPluginAsset::register($this);

$this->title = Yii::t('easyii/catalog', 'Catalog');

function renderNode($node)
{
    $html = '<tr>';
    $html .= '<td width="50">'.$node['category_id'].'</td>';
    $html .= '
        <td style="padding-left: '.($node['depth']*20).'px;">
            '.(sizeof($node['children']) ? '<i class="caret"></i> <span>'.$node['title'].'</span>' : '<a href="' . Url::to(['/admin/catalog/items', 'id' => $node['category_id']]) . '">'.$node['title'].'</a>').'
        </td>';
    $html .= '
        <td width="120" class="text-right">
            <div class="dropdown actions">
                <i id="dropdownMenu'.$node['category_id'].'" data-toggle="dropdown" aria-expanded="true" title="'.Yii::t('easyii', 'Actions').'" class="glyphicon glyphicon-menu-hamburger"></i>
                <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu'.$node['category_id'].'">
                    <li><a href="' . Url::to(['/admin/catalog/a/edit', 'id' => $node['category_id']]) . '"><i class="glyphicon glyphicon-pencil font-12"></i> '.Yii::t('easyii', 'Edit').'</a></li>
                    <li><a href="' . Url::to(['/admin/catalog/a/create', 'parent' => $node['category_id']]) . '"><i class="glyphicon glyphicon-plus font-12"></i> '.Yii::t('easyii', 'Add subcategory').'</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="' . Url::to(['/admin/catalog/a/up', 'id' => $node['category_id']]) . '"><i class="glyphicon glyphicon-arrow-up font-12"></i> '.Yii::t('easyii', 'Move up').'</a></li>
                    <li><a href="' . Url::to(['/admin/catalog/a/down', 'id' => $node['category_id']]) . '"><i class="glyphicon glyphicon-arrow-down font-12"></i> '.Yii::t('easyii', 'Move down').'</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="' . Url::to(['/admin/catalog/a/delete', 'id' => $node['category_id']]) . '" class="confirm-delete" data-reload="1" title="'.Yii::t('easyii', 'Delete item').'"><i class="glyphicon glyphicon-remove font-12"></i> '.Yii::t('easyii', 'Delete').'</a></li>
                </ul>
            </div>
        </td>
    ';
    $html .= '</tr>';

    if(sizeof($node['children'])){
        foreach($node['children'] as $child){
            $html .= renderNode($child);
        }
    }

    return $html;
}

?>

<?= $this->render('_menu') ?>

<?php if(sizeof($tree) > 0) : ?>
    <table class="table table-hover">
        <tbody>
        <?php foreach($tree as $node) echo renderNode($node); ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>