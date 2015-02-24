<?php
namespace yii\easyii\components;

use Yii;

class Module extends \yii\base\Module
{
    public $defaultRoute = 'a';
    public $settings = [];
    public $i18n;

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

        self::registerTranslations(self::className());
    }

    public static function registerTranslations($moduleName)
    {
        if(!ctype_alpha($moduleName) !== false) $moduleName = self::getModuleName($moduleName);

        Yii::$app->i18n->translations['easyii/'.$moduleName.'*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii/modules/'.$moduleName.'/messages',
            'fileMap' => [
                'easyii/'.$moduleName => 'admin.php',
                'easyii/'.$moduleName.'/api' => 'api.php'
            ]
        ];
    }

    public static function getModuleName($namespace)
    {
        foreach(\yii\easyii\models\Module::findAllActive() as $module)
        {
            $moduleClassPath = preg_replace('/[\w]+$/', '', $module->class);
            if(strpos($namespace, $moduleClassPath) !== false){
                return $module->name;
            }
        }
        return false;
    }
}