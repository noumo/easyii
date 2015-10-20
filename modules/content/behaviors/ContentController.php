<?php

namespace yii\easyii\modules\content\behaviors;

use yii\easyii\modules\content\api\Content;
use yii\easyii\modules\content\api\ItemObject;
use yii\easyii\modules\content\models\Item;
use yii\base\Behavior;
use yii\base\View;
use yii\web\Controller;

/**
 * Class BaseController
 * @package app\controllers
 *
 * @property ItemObject $content
 * @property Controller $owner
 */
class ContentController extends Behavior
{
    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]], [[renderPartial()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return View|\yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        $view = $this->owner->getView();

        if ($this->content->model) {
            $view->title = $this->content->seo('title', $this->content->model->title);
        }

        return $view;
    }

    private $_content;

    /**
     * @return ItemObject
     */
    public function getContent()
    {
        if ($this->_content == null)
        {
            /** @var Controller $controller */
            $controller = $this->owner;
            $idSlug = "{$controller->id}-{$controller->action->id}";

            $this->_content = Content::get($idSlug);
            if ($this->_content === null)
            {
                $this->_content = new ItemObject(new Item());
            }
        }

        return $this->_content;
    }
}