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