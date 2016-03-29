<?php
namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\modules\page\models\Page as PageModel;
use yii\helpers\Html;

/**
 * Page module API
 * @package yii\easyii\modules\page\api
 *
 * @method static PageObject get(mixed $id_slug) Get page object by id or slug
 */

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
        $page = PageModel::find()->where(['or', 'id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $page ? new PageObject($page) : $this->notFound($id_slug);
    }

    private function notFound($id_slug)
    {
        $config = ['slug' => $id_slug];
        if(IS_ROOT && preg_match(PageModel::$SLUG_PATTERN, $id_slug)){
            $config['title'] = $config['text'] = Html::a(Yii::t('easyii/page/api', 'Create text'), ['/admin/page/a/create', 'slug' => $id_slug], ['target' => '_blank']);
        }
        return new PageObject(new PageModel($config));
    }
}