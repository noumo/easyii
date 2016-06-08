<?php
namespace yii\easyii\helpers;

use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use \yii\web\HttpException;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class Upload
{
    static $RESTRICTED_EXTENSIONS = ['php', 'phtml', 'php5', 'htm', 'html', 'js', 'jsp', 'sh', 'exe', 'bat', 'com'];

    public static function file(UploadedFile $fileInstance, $dir = '', $namePostfix = true)
    {
        if(in_array($fileInstance->extension, self::$RESTRICTED_EXTENSIONS)){
            return false;
        }
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance, $namePostfix);

        if(!$fileInstance->saveAs($fileName)){
            throw new HttpException(500, 'Cannot upload file "'.$fileName.'". Please check write permissions.');
        }
        $path = ($dir ? $dir . DIRECTORY_SEPARATOR : '');
        return $path . basename($fileName);
    }

    static function getUploadPath($dir = null)
    {
        $uploadPath = Yii::getAlias('@uploads');
        if(!$uploadPath){
            throw new ServerErrorHttpException('Alias `@uploads` is not set.');
        }

        if($dir){
            $uploadPath .= DIRECTORY_SEPARATOR . $dir;
        }

        $webroot = Yii::getAlias('@webroot');

        $uploadPath = $webroot . str_replace('\\', '/', $uploadPath);

        if(!FileHelper::createDirectory($uploadPath)){
            throw new HttpException(500, 'Cannot create "'.$uploadPath.'". Please check write permissions.');
        }
        return $uploadPath;
    }

    static function getFileUrl($fileName)
    {
        return $fileName ? self::getPathUrl() . '/' . $fileName : '';
    }

    static function getPathUrl($dir = '')
    {
        $webroot = Yii::getAlias('@webroot');
        $uploads = Yii::getAlias('@uploads');

        $path = str_replace($webroot, '', str_replace('\\', '/', $uploads));

        if ($dir) {
            $path .= DIRECTORY_SEPARATOR . $dir;
        }

        return Yii::getAlias('@web') . $path;
    }

    static function getAbsolutePath($fileName)
    {
        if(!$fileName){
            return '';
        }
        if(strpos($fileName, Yii::getAlias('@uploads')) !== false ){
            return $fileName;
        } else {
            return Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . $fileName;
        }
    }

    static function delete($fileName)
    {
        if($fileName) {
            $filePath = self::getAbsolutePath($fileName);
            if(is_file($filePath)){
                @unlink($filePath);
            }
        }
    }

    static function getFileName(UploadedFile $fileInstance, $namePostfix = true)
    {
        $fileName =  StringHelper::truncate(Inflector::slug($fileInstance->baseName), 32, '');
        if($namePostfix || !$fileName) {
            $fileName .= ($fileName ? '-' : '') . substr(uniqid(md5(rand()), true), 0, 10);
        }
        $fileName .= '.' . strtolower($fileInstance->extension);

        return $fileName;
    }
}