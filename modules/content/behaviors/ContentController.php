<?php

namespace yii\easyii\modules\content\behaviors;

use yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\base\View;
use yii\easyii\modules\content\api\Content;
use yii\easyii\modules\content\api\ItemObject;
use yii\easyii\modules\content\models\Item;
use yii\web\Controller;

/**
 * Class BaseController
 *
 * @property Controller $owner
 * @property ItemObject $content
 * @property string $slug
 */
trait ContentController
{
    private $_slug;

    private $_content;

    public function beforeAction($action)
    {
        if ($this->content->model) {
            $this->view->title = Yii::$app->name . " - " . $this->content->seo('title', $this->content->model->title);
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
            if ($this->_content === null)
            {
                $this->_content = new ItemObject(new Item());
            }
        }

        return $this->_content;
    }

    public function actionContent($id = null)
    {
        if ($id) {
            $this->slug = $id;
        }

        return $this->render('@easyii/modules/content/views/layouts/default', [
            'content' => $this->content,
        ]);
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        if ($this->_slug === null) {
            /** @var Controller $controller */
            $controller = $this;
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
}