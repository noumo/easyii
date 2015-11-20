<?php
namespace yii\easyii\helpers;

use Yii;
use yii\easyii\models\Module;

class MigrationHelper
{
    public static  function appendModuleSettings($moduleName, $settings)
    {
        if(($module = Module::findOne(['name' => $moduleName])))
        {
            $module->settings = array_merge($module->settings, $settings);
            $module->save();
        }
    }
}