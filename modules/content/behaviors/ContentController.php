<?php

namespace yii\easyii\modules\content\behaviors;

use Yii;
use yii\easyii\modules\content\api\Content;
use yii\easyii\modules\content\api\ItemObject;
use yii\easyii\modules\content\models\Item;
use yii\web\Controller;

/**
 * Default implementation to handle the content items.
 *
 * @property Controller $controller
 * @property ItemObject $content
 * @property string $slug
 */
trait ContentController
{
    private $_slug;

    private $_content;

    public function actionContent($id = null)
    {
        if ($id) {
            $this->slug = $id;
        }

        if ($this->content == null) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('easyii', 'Not found'));
        }

	    return $this->renderContentView();
    }

	public function renderContentView(array $params = [])
    {
	    $this->viewTitle();

	    $params['content'] = $this->content;

        $viewFile = $this->getContentViewPath();
        $viewContent = $this->controller->view->render($viewFile, $params, $this);

        $layoutFile = $this->getLayoutFile();
        $content = $this->controller->view->renderFile($layoutFile, ['content' => $viewContent], $this);

        return $this->controller->renderContent($content);
    }

	public function viewTitle()
	{
		if ($this->content->model) {
			/** @var \yii\web\View $view */
			$view = $this->controller->view;
			$view->title = \Yii::$app->name . " - " . $this->content->seo('title', $this->content->model->title);
		}
	}

	/**
     * @return string
     */
    protected function getContentViewPath()
    {
        $view =  $this->content->slug;
        $viewFile = Yii::getAlias($this->controller->viewPath . DIRECTORY_SEPARATOR . $view . '.php');

        if (is_file($viewFile)) {
            return $view;
        }

        $view = 'default';
        $viewFile = Yii::getAlias($this->controller->viewPath . DIRECTORY_SEPARATOR . $view . '.php');

        if (is_file($viewFile)) {
            return $view;
        }

        return '@easyii/modules/content/views/content/default';
    }

    /**
     * @return string
     */
    protected function getLayoutFile()
    {
        $layoutPath = $this->controller->module->layoutPath . DIRECTORY_SEPARATOR . 'content';

        $layout = $this->content->getLayout()->slug;
        if ($layout !== null) {
            if ($layoutFile = $this->findContentLayout($layoutPath, $layout)) {
                return $layoutFile;
            }

            Yii::warning("No file for the layout '$layout' found under '$layoutPath'", 'easyii/content');
        }

        $layout = 'default';
        if ($layoutFile = $this->findContentLayout($layoutPath, $layout)) {
            return $layoutFile;
        }

        $layoutFile = '@easyii/modules/content/views/layouts/default.php';
        return $layoutFile;
    }

    private function findContentLayout($layoutPath, $layout)
    {
        $view = $layoutPath . DIRECTORY_SEPARATOR . $layout;
        $viewFile = Yii::getAlias($view) . '.php';

        if (is_file($viewFile)) {
            return $viewFile;
        }

        return false;
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
            $this->_slug = $this->controller->action->id;
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