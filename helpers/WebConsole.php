<?php
namespace yii\easyii\helpers;

use Yii;
use yii\console\Application;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;

/**
 * Class WebConsole
 * @package yii\easyii\helpers
 */
class WebConsole
{
    private static $_console;
    public static $logFile;
    public static $logFileHandler;

    /**
     * Web console init
     *
     * @return Application
     * @throws \yii\base\Exception
     * @throws ServerErrorHttpException
     */
    public static function console()
    {
        if(!self::$_console)
        {
            $logsPath = Yii::getAlias('@runtime/logs');
            if(!FileHelper::createDirectory($logsPath, 0777)){
                throw new ServerErrorHttpException('Cannot create `'.$logsPath.'`. Please check write permissions.');
            }

            self::$logFile = $logsPath . DIRECTORY_SEPARATOR . 'console.log';
            self::$logFileHandler = fopen(self::$logFile, 'w+');

            defined('STDIN') or define( 'STDIN',  self::$logFileHandler);
            defined('STDOUT') or define( 'STDOUT',  self::$logFileHandler);

            $oldApp = Yii::$app;

            $consoleConfigFile = Yii::getAlias('@app/config/console.php');

            if(!file_exists($consoleConfigFile) || !is_array(($consoleConfig = require($consoleConfigFile)))){
                throw new ServerErrorHttpException('Cannot find `'.Yii::getAlias('@app/config/console.php').'`. Please create and configure console config.');
            }

            self::$_console = new Application($consoleConfig);

            Yii::$app = $oldApp;
        } else {
            ftruncate(self::$logFileHandler, 0);
        }

        return self::$_console;
    }

    /**
     * Run migrations
     *
     * @param string $migrationPath
     * @return string
     * @throws \yii\console\Exception
     * @throws ServerErrorHttpException
     */
    public static function migrate($migrationPath = '@easyii/migrations/')
    {
        ob_start();

        self::console()->runAction('migrate', ['migrationPath' => $migrationPath, 'migrationTable' => 'easyii_migration', 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
}