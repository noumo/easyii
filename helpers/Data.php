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
        return [
            str_replace('_', '-', Yii::$app->language),
            str_replace('-', '_', Yii::$app->language),
            str_replace('_', '-', strtolower(Yii::$app->language)),
            str_replace('-', '_', strtolower(Yii::$app->language)),
            preg_split('/[-_]/', strtolower(Yii::$app->language))[0],
        ];
    }
}