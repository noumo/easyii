<?php
namespace yii\easyii\components;

use Yii;

class API extends \yii\base\Object
{
    static $classes;
    public $module;

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

    public function wrapLiveEdit($text, $path, $tag = 'span')
    {
        return '<'.$tag.' class="easyiicms-edit" data-edit="/admin/'.$this->module.'/'.$path.'">'.$text.'</'.$tag.'>';
    }

    public function  errorText($text)
    {
        return '<span style="background: #ff0000; color: #ffffff">'.$text.'</span>';
    }
}