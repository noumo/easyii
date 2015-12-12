<?php

namespace yii\easyii\modules\content\behaviors;

use yii;
use yii\base\ActionEvent;
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

    private $_content;

    /**
     * Declares event handlers for the [[owner]]'s events.
     *
     * Child classes may override this method to declare what PHP callbacks should
     * be attached to the events of the [[owner]] component.
     *
     * The callbacks will be attached to the [[owner]]'s events when the behavior is
     * attached to the owner; and they will be detached from the events when
     * the behavior is detached from the component.
     *
     * The callbacks can be any of the following:
     *
     * - method in this behavior: `'handleClick'`, equivalent to `[$this, 'handleClick']`
     * - object method: `[$object, 'handleClick']`
     * - static method: `['Page', 'handleClick']`
     * - anonymous function: `function ($event) { ... }`
     *
     * The following is an example:
     *
     * ~~~
     * [
     *     Model::EVENT_BEFORE_VALIDATE => 'myBeforeValidate',
     *     Model::EVENT_AFTER_VALIDATE => 'myAfterValidate',
     * ]
     * ~~~
     *
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'handleBeforeAction'
        ];
    }

    public function handleBeforeAction(ActionEvent $event)
    {
        if ($this->content->model) {
            $this->owner->view->title = Yii::$app->name . " - " . $this->content->seo('title', $this->content->model->title);
        }
    }

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