<?php
namespace yii\easyii\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\easyii\AdminModule;
use yii\easyii\components\Module;
use yii\easyii\models;
use yii\easyii\components\Controller;

/**
 * The help controller show markdown files with help and info of the module.
 * Default path of the markdown file ist [modulePath/help/readme.md].
 *
 * Implementation example:
 *
 * public $controllerMap = [
 *      'help' => 'yii\easyii\controllers\HelpController'
 * ];
 *
 * @property Module $module
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class HelpController extends Controller
{
    public $readmeView = '@easyii/views/help/view';

    private $_readmeFile = null;

    public $defaultAction = 'view';

    public function actionView()
    {
        if ($this->readmeFile !== false) {
            $readmeContent = $this->getReadmeContent();
        }

        return $this->render($this->readmeView, [
            'module' => Yii::$app->getModule('admin')->activeModules[$this->module->selfName],
            'readmeContent' => $readmeContent,
        ]);
    }

    /**
     * Returns the directory that contains the readme files for this module.
     * @return string the root directory of readme file. Defaults to "[[basePath]]/help/readme.md".
     */
    public function getReadmeFile()
    {
        if ($this->_readmeFile === null) {
            $this->_readmeFile = $this->module->getBasePath() . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . 'readme.md';
        }
        return $this->_readmeFile;
    }

    /**
     * Sets the directory that contains the readme files.
     * @param string $path the root directory of readme files.
     * @throws InvalidParamException if the directory is invalid
     */
    public function setReadmeFile($path)
    {
        $this->_readmeFile = Yii::getAlias($path);
    }

    /**
     * @return string
     */
    public function getReadmeContent()
    {
        try {
            $readmeContent = file_get_contents($this->readmeFile);
        }
        catch (\Exception $ex) {
            $readmeContent = Yii::t('easyii', 'No help file found for this module.');
            $readmeContent .= "\n\n*(" . $this->readmeFile . ")*";
        }

        return $readmeContent;
    }
}