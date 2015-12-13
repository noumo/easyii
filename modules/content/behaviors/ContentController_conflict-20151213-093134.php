<?php

namespace yii\easyii\modules\content\behaviors;

use yii;
use yii\easyii\modules\content\api\Content;
use yii\easyii\modules\content\api\ItemObject;
use yii\easyii\modules\content\models\Item;
use yii\web\Controller;

/**
 * Class BaseController
 *
 * @property Controller $controller
 * @property ItemObject $content
 * @property string $slug
 */
trait ContentController
{
    public $viewPath = '@easyii/modules/content/views/layouts/default';

    private $_slug;

    private $_content;

    public function actionContent($id = null)
    {
        if ($id) {
            $this->slug = $id;
        }

        $content = $this->content;

        if ($content == null) {
            throw new yii\web\NotFoundHttpException(\Yii::t('easyii', 'Not found'));
        }

        $this->handleBeforeRender();

        return $this->render($this->viewPath, [
            'content' => $content,
        ]);
    }

    public function render($view, $params = [])
    {
        $content = $this->controller->view->render($view, $params, $this);
        $content = $this->getView()->renderFile($layoutFile, ['content' => $content], $this);

        return $this->controller->renderContent($content);
    }

    public function handleBeforeRender()
    {
        if ($this->content->model) {
            /** @var yii\web\View $view */
            $view = $this->controller->view;
            $view->title = \Yii::$app->name . " - " . $this->content->seo('title', $this->content->model->title);
        }
    }

    /**
     * @return ItemObject
     */
    public function getContent()
    {
        if ($this->_content == null)
        {
            $this->_content = Content::get($this->slug);

            if ($this->_content === null && LIVE_EDIT)
            {
                $this->_content = new ItemObject(new Item());
            }
        }

        return $this->_content;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        if ($this->_slug === null) {
            $controller = $this->controller;
            $this->_slug = "{$controller->id}-{$controller->action->id}";
        }

        return $this->_slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->_slug = $slug;
    }

    public function getController()
    {
        return $this;
    }
}