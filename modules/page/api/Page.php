<?php
namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\modules\page\models\Page as PageModel;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

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

    public function api_menu()
    {
        $result = [];
        foreach(PageModel::tree() as $page) {
            if($page->show_in_menu)
            {
                $temp = $this->buildMenuItem($page);
                $temp['children'] = [];
                if (!empty($page->children)) {
                    foreach ($page->children as $child) {
                        if($child->show_in_menu) {
                            $temp['children'][] = $this->buildMenuItem($child);
                        }
                    }
                }
                $result[] = $temp;
            }
        }
        return $result;
    }

    private function findPage($id_slug)
    {
        try {
            $result = new PageObject(PageModel::get($id_slug));
        } catch (NotFoundHttpException $e) {
            $result = $this->notFound($id_slug);
        }

        return $result;
    }

    private function notFound($id_slug)
    {
        $config = ['slug' => $id_slug];
        if(IS_ROOT && preg_match(PageModel::$SLUG_PATTERN, $id_slug)){
            $config['title'] = $config['text'] = Html::a(Yii::t('easyii/page/api', 'Create page'), ['/admin/page/a/create', 'slug' => $id_slug], ['target' => '_blank']);
        } else {
            throw new NotFoundHttpException(Yii::t('easyii', 'Page not found'));
        }
        return new PageObject(new PageModel($config));
    }

    private function buildMenuItem($page)
    {
        $result = [
            'label' => $page->title,
            'active' => false
        ];
        $controller = Yii::$app->controller;

        if($page->slug == 'index') {
            $result['url'] = \yii\helpers\Url::home();
            if($controller->id == 'site' && $controller->action->id == 'index') {
                $result['active'] = true;
            }
        } else {
            if($page->depth == 0) {
                $result['url'] = \yii\helpers\Url::to(['/' . $page->slug]);
                if($controller->id == $page->slug) {
                    $result['active'] = true;
                }
            } else {
                $result['url'] = \yii\helpers\Url::to(['/' . PageModel::get($page->parent)->slug . '/' . $page->slug]);
            }
        }
        return $result;
    }
}