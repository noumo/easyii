<?php
namespace yii\easyii\components;

use Yii;
use yii\base\InvalidParamException;
use yii\easyii\models\Module as ModuleModel;

/**
 * Base module class. Inherit from this if you are creating your own modules manually
 *
 * @property string $readmePath
 *
 * @package yii\easyii\components
 */
class Module extends \yii\base\Module
{
    private $_readmePath = 'help/readme.md';

    /** @var string  */
    public $defaultRoute = 'a';

    /** @var array  */
    public $settings = [];

    /** @var  @todo */
    public $i18n;

    public static $NAME;

    /**
     * Configuration for installation
     * @var array
     */
    public static $installConfig = [
        'title' => [
            'en' => 'Custom Module',
        ],
        'icon' => 'asterisk',
        'order_num' => 0,
    ];

    public function init()
    {
        parent::init();
        static::registerTranslations(static::getSelfName());
    }

    public static function getSelfName()
    {
        if(!static::$NAME){
            static::$NAME = static::getModuleName(static::className());
        }
        return static::$NAME;
    }

    /**
     * Registers translations connected to the module
     * @param $moduleName string
     */
    public static function registerTranslations($moduleName)
    {
        $moduleClassFile = '';
        foreach(ModuleModel::findAllActive() as $name => $module){
            if($name == $moduleName){
                $moduleClassFile = (new \ReflectionClass($module->class))->getFileName();
                break;
            }
        }

        if($moduleClassFile){
            Yii::$app->i18n->translations['easyii/'.$moduleName.'*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => dirname($moduleClassFile) . DIRECTORY_SEPARATOR . 'messages',
                'fileMap' => [
                    'easyii/'.$moduleName => 'admin.php',
                    'easyii/'.$moduleName.'/api' => 'api.php'
                ]
            ];
        }
    }

    /**
     * Module name getter
     *
     * @param $namespace
     * @return string|bool
     */
    public static function getModuleName($namespace)
    {
        foreach(ModuleModel::findAllActive() as $module)
        {
            $moduleClassPath = preg_replace('/[\w]+$/', '', $module->class);
            if(strpos($namespace, $moduleClassPath) !== false){
                return $module->name;
            }
        }
        return false;
    }

    public static function setting($name)
    {
        $settings = Yii::$app->getModule('admin')->activeModules[static::getSelfName()]->settings;
        return $settings[$name] !== null ? $settings[$name] : null;
    }

    /**
     * Returns the directory that contains the view files for this module.
     * @return string the root directory of view files. Defaults to "[[basePath]]/views".
     */
    public function getReadmePath()
    {
        if ($this->_readmePath === null) {
            $this->_readmePath = $this->getBasePath() . DIRECTORY_SEPARATOR . 'help';
        }
        return $this->_readmePath;
    }

    /**
     * Sets the directory that contains the view files.
     * @param string $path the root directory of view files.
     * @throws InvalidParamException if the directory is invalid
     */
    public function setReadmePath($path)
    {
        $this->_readmePath = Yii::getAlias($path);
    }
}