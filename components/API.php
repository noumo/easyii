<?php
namespace yii\easyii\components;

use Yii;

class API extends \yii\base\Object
{
    static $classes;
    public $module;
    public $defaultLiveOptions = [
        'tag' => 'span',
        'action' => 'edit',
        'hash' => ''
    ];

    public function init()
    {
        parent::init();

        $this->module = Module::getModuleName(self::className());
        Module::registerTranslations($this->module);
    }

    public static function __callStatic($method, $params)
    {
        $name = (new \ReflectionClass(self::className()))->getShortName();
        if (!isset(self::$classes[$name])) {
            self::$classes[$name] = new static();
        }
        return call_user_func_array([self::$classes[$name], 'api_' . $method], $params);
    }

    public static function cache($key, $duration, $callable)
    {
        $cache = Yii::$app->cache;
        if($cache->exists($key)){
            $data = $cache->get($key);
        }
        else{
            $data = $callable();
            $cache->set($key, $data, $duration);
        }
        return $data;
    }

    public function wrapLiveEdit($text, $id, $options = null)
    {
        if($options !== null){
            $options = array_merge($this->defaultLiveOptions, $options);
        }
        else{
            $options = $this->defaultLiveOptions;
        }
        return '<'.$options['tag'].' class="easyiicms-edit" data-module="'.$this->module.'" data-action="'.$options['action'].'" data-id="'.$id.'" data-hash="'.$options['hash'].'" >'.$text.'</'.$options['tag'].'>';
    }

    public function  errorText($text)
    {
        return '<span style="background: #ff0000; color: #ffffff">'.$text.'</span>';
    }
}