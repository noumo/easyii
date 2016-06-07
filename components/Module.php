<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\models\Module as ModuleModel;

/**
 * Base module class. Inherit from this if you are creating your own modules manually
 * @package yii\easyii\components
 */
class Module extends \yii\base\Module
{
    /** @var string  */
    public $defaultRoute = 'a';

    /** @var array  */
    public $settings = [];

    /** @var  @todo */
    public $i18n;

    private static $NAMES = [];

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
        $className = static::className();
        if(!isset(self::$NAMES[$className])){
            self::$NAMES[$className] = self::getModuleName($className);
        }
        return self::$NAMES[$className];
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
        return isset($settings[$name]) ? $settings[$name] : null;
    }
}