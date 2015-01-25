<?php
namespace yii\easyii\models;

use Yii;

use yii\easyii\helpers\Data;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SortableModel;

class Module extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF= 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'easyii_modules';

    public static function tableName()
    {
        return 'easyii_modules';
    }

    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['name', 'title', 'icon'], 'trim'],
            ['name',  'match', 'pattern' => '/^[a-z]+$/'],
            ['name', 'unique'],
            ['icon', 'string'],
            ['status', 'in', 'range' => [0,1]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii', 'Name'),
            'title' => Yii::t('easyii', 'Title'),
            'icon' => Yii::t('easyii', 'Icon'),
            'order_num' => Yii::t('easyii', 'Order'),
        ];
    }


    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->settings || !is_array($this->settings)){
                $this->settings = self::getDefaultSettings($this->name);
            }
            $this->settings = json_encode($this->settings);

            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->settings = $this->settings !== '' ? json_decode($this->settings, true) : self::getDefaultSettings($this->name);
    }

    public static function findAllActive()
    {
        return Data::cache(self::CACHE_KEY, 3600, function(){
            $result = [];
            try {
                foreach (self::find()->where(['status' => self::STATUS_ON])->sort()->all() as $module) {
                    $module->trigger(self::EVENT_AFTER_FIND);
                    $result[$module->name] = (object)$module->attributes;
                }
            }catch(\yii\db\Exception $e){}

            return $result;
        });
    }

    public function setSettings($settings)
    {
        $newSettings = [];
        foreach($this->settings as $key => $value){
            $newSettings[$key] = is_bool($value) ? ($settings[$key] ? true : false) : ($settings[$key] ? $settings[$key] : '');
        }
        $this->settings = $newSettings;
    }

    static function getDefaultSettings($moduleName)
    {
        return Yii::createObject('\\yii\\easyii\\modules\\'.$moduleName.'\\'.ucfirst($moduleName).'Module', [$moduleName])->settings;
    }

}