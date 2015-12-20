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
    private $_readmePath = null;

    public function actionView($moduleName)
    {
        $readmeContent = file_get_contents($this->readmePath);

        return $this->render('@easyii/views/help/view', [
            'module' => Yii::$app->getModule('admin')->activeModules[$moduleName],
            'readmeContent' => $readmeContent,
        ]);
    }

    /**
     * Returns the directory that contains the readme files for this module.
     * @return string the root directory of readme file. Defaults to "[[basePath]]/help/readme.md".
     */
    public function getReadmeFile()
    {
        if ($this->_readmePath === null) {
            $this->_readmePath = $this->module->getBasePath() . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . 'readme.md';
        }
        return $this->_readmePath;
    }

    /**
     * Sets the directory that contains the readme files.
     * @param string $path the root directory of readme files.
     * @throws InvalidParamException if the directory is invalid
     */
    public function setReadmeFile($path)
    {
        $this->_readmePath = Yii::getAlias($path);
    }
}