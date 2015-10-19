<?php
namespace yii\easyii\helpers;

use Yii;
use yii\easyii\models\Module;

class Migration
{
    public static  function appendModuleSettings($moduleName, $settings)
    {
        if(($module = Module::findOne(['name' => $moduleName])))
        {
            $module->appendSettings($settings);
            $module->save();
        }
    }
}