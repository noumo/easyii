<?php
namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\modules\page\models\Page as PageModel;
use yii\helpers\Html;

class Page extends \yii\easyii\components\API
{
    private $_pages = [];

    public function api_get($id_slug)
    {
        if(!isset($this->_pages[$id_slug])) {
            $this->_pages[$id_slug] = $this->findPage($id_slug);
        }
        return $this->_pages[$id_slug];
    }

    private function findPage($id_slug)
    {
        $page = PageModel::find()->where(['or', 'page_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $page ? new PageObject($page) : $this->notFound($id_slug);
    }

    private function notFound($id_slug)
    {
        $page = new PageModel();

        if(!Yii::$app->user->isGuest && preg_match(PageModel::$SLUG_PATTERN, $id_slug)){
            $a = Html::a(Yii::t('easyii/page/api', 'Create page'), ['/admin/page/a/create', 'slug' => $id_slug], ['target' => '_blank']);
            $page->title = $a;
            $page->text = $a;

        }

        return new PageObject($page);
    }
}