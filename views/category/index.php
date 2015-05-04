<?php
use yii\easyii\components\CategoryModel;
use yii\helpers\Url;

\yii\bootstrap\BootstrapPluginAsset::register($this);

$this->title = Yii::$app->getModule('admin')->activeModules[$this->context->module->id]->title;

$module = $this->context->module->id;



function renderNode($node, $baseUrl)
{
    $html = '<tr>';
    $html .= '<td width="50">'.$node['category_id'].'</td>';
    $html .= '<td style="padding-left: '.($node['depth']*20).'px;">';
    if(sizeof($node['children'])){
        $html .= '<i class="caret"></i> ';
    }
    if(!count($node['children']) || !empty(Yii::$app->controller->module->settings['itemsInFolder'])) {
        $html .= '<a href="' . Url::to([$baseUrl . '/items', 'id' => $node['category_id']]) . '" ' . (($node['status'] == CategoryModel::STATUS_OFF) ? 'class="smooth"' : '') . '>' . $node['title'] . '</a>';
    } else {
        $html .= '<span ' . (($node['status'] == CategoryModel::STATUS_OFF) ? 'class="smooth"' : '') . '>' . $node['title'] . '</span>';
    }
    $html .= '</td>';

    if($node['status'] == CategoryModel::STATUS_ON){
        $switchButton = '<li><a href="' . Url::to([$baseUrl.'/a/off', 'id' => $node['category_id']]) . '" title="'.Yii::t('easyii', 'Turn Off').'"><i class="glyphicon glyphicon-eye-close font-12"></i> '.Yii::t('easyii', 'Turn Off').'</a></li>';
    } else {
        $switchButton = '<li><a href="' . Url::to([$baseUrl.'/a/on', 'id' => $node['category_id']]) . '" title="'.Yii::t('easyii', 'Turn On').'"><i class="glyphicon glyphicon-eye-open font-12"></i> '.Yii::t('easyii', 'Turn On').'</a></li>';
    }

    $html .= '
        <td width="120" class="text-right">
            <div class="dropdown actions">
                <i id="dropdownMenu'.$node['category_id'].'" data-toggle="dropdown" aria-expanded="true" title="'.Yii::t('easyii', 'Actions').'" class="glyphicon glyphicon-menu-hamburger"></i>
                <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu'.$node['category_id'].'">
                    <li><a href="' . Url::to([$baseUrl.'/a/edit', 'id' => $node['category_id']]) . '"><i class="glyphicon glyphicon-pencil font-12"></i> '.Yii::t('easyii', 'Edit').'</a></li>
                    <li><a href="' . Url::to([$baseUrl.'/a/create', 'parent' => $node['category_id']]) . '"><i class="glyphicon glyphicon-plus font-12"></i> '.Yii::t('easyii', 'Add subcategory').'</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="' . Url::to([$baseUrl.'/a/up', 'id' => $node['category_id']]) . '"><i class="glyphicon glyphicon-arrow-up font-12"></i> '.Yii::t('easyii', 'Move up').'</a></li>
                    <li><a href="' . Url::to([$baseUrl.'/a/down', 'id' => $node['category_id']]) . '"><i class="glyphicon glyphicon-arrow-down font-12"></i> '.Yii::t('easyii', 'Move down').'</a></li>
                    <li role="presentation" class="divider"></li>
                    '.$switchButton.'
                    <li><a href="' . Url::to([$baseUrl.'/a/delete', 'id' => $node['category_id']]) . '" class="confirm-delete" data-reload="1" title="'.Yii::t('easyii', 'Delete item').'"><i class="glyphicon glyphicon-remove font-12"></i> '.Yii::t('easyii', 'Delete').'</a></li>
                </ul>
            </div>
        </td>
    ';
    $html .= '</tr>';

    if(sizeof($node['children'])){
        foreach($node['children'] as $child){
            $html .= renderNode($child, $baseUrl);
        }
    }

    return $html;
}

?>

<?= $this->render('_menu') ?>

<?php if(sizeof($tree) > 0) : ?>
    <table class="table table-hover">
        <tbody>
            <?php foreach($tree as $node) echo renderNode($node, '/admin/'.$this->context->moduleName); ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>