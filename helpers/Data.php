<?php
namespace yii\easyii\helpers;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Data
{
    public static function cache($key, $duration, $callable)
    {
        $cache = Yii::$app->cache;
        if($cache->exists($key)){
            $data = $cache->get($key);
        }
        else{
            $data = $callable();

            if($data) {
                $cache->set($key, $data, $duration);
            }
        }
        return $data;
    }

    public static function getLocale()
    {
        $muti_lan_array=['zh-cn','zh-tw'];
		$language=strtolower(trim(Yii::$app->language));
        return in_array($language,$muti_lan_array,false)?$language:substr($language, 0, 2); 
    }
}
