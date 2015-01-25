<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\easyii\models\Module;

class CalculateNotice extends \yii\base\Behavior
{
    public $callback;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateNotice',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateNotice',
            ActiveRecord::EVENT_AFTER_DELETE => 'updateNotice',
        ];
    }

    public function updateNotice()
    {
        $moduleName = \yii\easyii\components\Module::getModuleName(get_class($this->owner));
        if(($module = Module::findOne(['name' => $moduleName]))){
            $module->notice = call_user_func($this->callback);
            $module->update();
        }
    }
}