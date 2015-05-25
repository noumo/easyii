<?php
namespace yii\easyii\components;

use Yii;

/**
 * Base API component. Used by all modules
 * @package yii\easyii\components
 */
class API extends \yii\base\Object
{
    /** @var  array */
    static $classes;
    /** @var  string module name */
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

    /**
     * Wrap text with liveEdit tags, which later will fetched by jquery widget
     * @param $text
     * @param $path
     * @param string $tag
     * @return string
     */
    public static  function liveEdit($text, $path, $tag = 'span')
    {
        return $text ? '<'.$tag.' class="easyiicms-edit" data-edit="'.$path.'">'.$text.'</'.$tag.'>' : '';
    }
}
