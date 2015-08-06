<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\easyii\components\Module as ModuleComponent;
use yii\easyii\models\Module as ModuleModel;

class CalculateNotice extends Behavior
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
        $moduleName = ModuleComponent::getModuleName(get_class($this->owner));
        if(($module = ModuleModel::findOne(['name' => $moduleName]))){
            $module->notice = call_user_func($this->callback);
            $module->update();
        }
    }
}